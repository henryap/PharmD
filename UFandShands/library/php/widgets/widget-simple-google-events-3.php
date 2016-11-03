<?php
include_once(ABSPATH . WPINC . '/feed.php');

class google_calendar_parser {
    public $GoogleAccounts;
    public $EventDays;
    public $EventCount;
    //public $accounts = split(',', $googleaccount);

    function google_calendar_parser($accounts, $numDays, $numEvents) {
        $this->GoogleAccounts = $accounts;
        $this->EventDays = $numDays;
        $this->EventCount = $numEvents;
    }
    
    function ListEvents() {
        $accounts = split(',', $this->GoogleAccounts);
        
    /////////
    //Configuration
    //
    // Date format you want your details to appear
    $dateformat = "n/j/Y";
    $timeformat = "g:ia";

    //Time offset - if times are appearing too early or too late on your website, change this.
    $offset = "+1"; // you can use "+1 hour" here for example
    // How you want each thing to display.
    // By default, this contains all the bits you can grab. You can put ###DATE### in here too if
    // you want to, and disable the 'group by date' below.
    $event_display = "<div class='item'><div class='event-date'>###DATE###</div><h4><a href='###LINK###'>###TITLE###</a></h4><p class='time'>###TIME###</p></div>";

    // The separate date header is here
    $event_dateheader = "";
    $GroupByDate = true;
    // Change the above to 'false' if you don't want to group this by dates,
    // but remember to add ###DATE### in the event_display if you do.
    // ...and how many you want to display (leave at 999 for everything)
    // $eventcount=2;
    //Where your simplepie.inc is (mine's in the root for some reason)


    // We'll use this for re-sorting the items based on the new date.
    $temp = array();  
    $hasError = false;
        
        foreach ($accounts as $account) {
            $calendar_xml_address = "http://www.google.com/calendar/feeds/" . trim($account) . "/public/full?singleevents=true&max-results=50&futureevents=true&orderby=starttime&sortorder=ascending";
            
            // Set the offset correctly
            // $offset=(strtotime("now")-strtotime($offset));
            $offset = (strtotime("now"));
            if ($debug_mode) {
                echo "Offset is " . $offset;
            }

            // Let's create a new SimplePie object
            $feed = fetch_feed($calendar_xml_address);

            if (!is_wp_error($feed)) {
                
                foreach ($feed->get_items() as $item) {
                    // We want to grab the Google-namespaced <gd:when> tag.
                    $when = $item->get_item_tags('http://schemas.google.com/g/2005', 'when');

                    // Now, let's grab the Google-namespaced <gd:where> tag.
                    $gd_where = $item->get_item_tags('http://schemas.google.com/g/2005', 'where');
                    $location = $gd_where[0]['attribs']['']['valueString'];
                    //and the status tag too, come to that
                    $gd_status = $item->get_item_tags('http://schemas.google.com/g/2005', 'eventStatus');
                    $status = substr($gd_status[0]['attribs']['']['value'], -8);

                    $when = $item->get_item_tags('http://schemas.google.com/g/2005', 'when');
                    $startdate = $when[0]['attribs']['']['startTime'];
                    $enddate = $when[0]['attribs']['']['endTime'];

                    $where = $item->get_item_tags('http://schemas.google.com/g/2005', 'where');
                    $location = $where[0]['attribs']['']['valueString'];

                    // If there's actually a title here (private events don't have titles) and it's not cancelled...
                    if (strlen(trim($item->get_title())) > 1 && $status != "canceled" && strlen(trim($startdate)) > 0) {
                        $temp[] = array('simplestartdate' => $startdate, 'simpleenddate' => $enddate, 'where' => $location, 'title' => $item->get_title(), 'description' => $item->get_description(), 'link' => $item->get_link());
                        if ($debug) {
                            echo "Added " . $item->get_title();
                        }
                    }
                }
            } else {
                $hasError = true;
            }
        }


        //if (!$hasError) {
        //Sort this 
        sort($temp);

        if ($debug) {
            print_r($temp);
        }

        $items_shown = 0;
        $old_date = "";

        $event_array_count = count($temp);
        if ($event_array_count == 0) {
            echo "<p><em>No events currently scheduled. </em></p>";
        }

        $event_listing = '';

        if (isset($temp)) {
            // Loop through the (now sorted) array, and display what we wanted.
            foreach ($temp as $item) {
                // These are the dates we'll display

                date_default_timezone_set('America/New_York');
                $simplegCalStartTime = strtotime($item['simplestartdate']);
                $simplegCalEndTime = strtotime($item['simpleenddate']);

                $simpledateconverted = "<span class='month'>";
                $simpledateconverted .= date('M', $simplegCalStartTime);
                $simpledateconverted .= "</span><span class='day'>";
                $simpledateconverted .= date('j', $simplegCalStartTime);
                $simpledateconverted .= "</span>";

                $simpledateconvertednoyear = date('n/j', $simplegCalStartTime);
                $simplestartconverted = date('g:ia', $simplegCalStartTime);
                $simpleendconverted = date('g:ia', $simplegCalEndTime);

                //Make any URLs used in the description also clickable: thanks Adam
                $item['description'] = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?,&//=]+)', '<a href="\\1">\\1</a>', $item['description']);

                // Now, let's run it through some str_replaces, and store it with the date for easy sorting later
                $temp_event = $event_display;
                $temp_dateheader = $event_dateheader;
                $temp_event = str_replace("###TITLE###", $item['title'], $temp_event);
                $temp_event = str_replace("###DESCRIPTION###", $item['description'], $temp_event);
                $temp_event = str_replace("###DATE###", $simpledateconverted, $temp_event);
                $temp_event = str_replace("###DATENOYEAR###", $simpledateconvertednoyear, $temp_event);

                if ($simplestartconverted == $simpleendconverted) {
                    $temp_event = str_replace("###TIME###", "all day event", $temp_event);
                } else {
                    $temp_event = str_replace("###TIME###", "From " . $simplestartconverted . " until " . $simpleendconverted, $temp_event);
                }

                $temp_event = str_replace("###WHERE###", $item['where'], $temp_event);
                $temp_event = str_replace("###LINK###", $item['link'] . "&amp;ctz=America/New_York", $temp_event);
                $temp_event = str_replace("###MAPLINK###", "http://maps.google.com/?q=" . urlencode($item['where']), $temp_event);
                // Accept and translate HTML
                $temp_event = str_replace("&lt;", "<", $temp_event);
                $temp_event = str_replace("&gt;", ">", $temp_event);
                $temp_event = str_replace("&quot;", "\"", $temp_event);

                $event_date_converted = ((($simplegCalStartTime / 60) / 60) / 24);
                $todays_date = (((time() / 60) / 60) / 24);
                $todays_date_plus2 = (((time() / 60) / 60) / 24) + 2;


                if ($this->EventCount > 1) {
                    if (($this->EventCount > 0 AND $items_shown < $this->EventCount)) {
                        if ($GroupByDate) {
                            if ($gCalDate != $old_date) {
                                echo $temp_dateheader;
                                $old_date = $gCalDate;
                            }
                        }
                        $event_listing .= $temp_event;

                        $items_shown++;
                    }
                } else {
                    if (($event_date_converted - $todays_date) >= 0 && ($event_date_converted - $todays_date) <= $this->EventDays) {
                        if ($GroupByDate) {
                            if ($gCalDate != $old_date) {
                                echo $temp_dateheader;
                                $old_date = $gCalDate;
                            }
                        }
                        
                        $event_listing .= $temp_event;

                        $items_shown++;
                    }
                }
            }
        }
    
        return $event_listing;

        if ($debug_mode) {
            echo "<PRE>";
            echo wordwrap(highlight_string(file_get_contents($calendar_xml_address), true), 80);
            echo "</pre>";
        }
        /*} elseif (is_wp_error($feed)) {
        echo "Ooops.. looks like something broke with your calendar -- Are you sure your calendar is set to public? Please <a href=\"mailto:webservices@ahc.ufl.edu\">contact AHC IT Web Services</a><br /><br />";
        echo $feed->get_error_message();
        echo "<br /><br />";
        } //end my error if statement
         */
    }
}
?>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sharepoint-calendar
 *
 * @author seanj
 */
class sharepoint_calendar_helper {

   
    public static function GetEvents($wsdl, $listName, $startDate, $rowLimit='150', $username=SHAREPOINT_USER, $password=SHAREPOINT_USER_PW) {

        //XML for the request. Add extra fields as necessary
        $query = '
        <GetListItems xmlns="http://schemas.microsoft.com/sharepoint/soap/">
            <listName>' . $listName . '</listName>
            <rowLimit>' . $rowLimit . '</rowLimit>
            <query>
                <Query>
                    <Where>
                        <And>
                            <DateRangesOverlap><FieldRef Name="EventDate" /><FieldRef Name="EndDate" /><FieldRef Name="RecurrenceID" /><Value Type="DateTime"><Month /></Value></DateRangesOverlap>
                            <Eq>
                                <FieldRef Name="Category" />
                                <Value Type="Text">Public Event</Value>
                            </Eq>
                        </And>
                    </Where>
                </Query>
            </query>
            <queryOptions>
                    <QueryOptions>
                        <ExpandRecurrence>TRUE</ExpandRecurrence>
                        <CalendarDate>' . $startDate . '</CalendarDate>
                    </QueryOptions>
            </queryOptions>
        </GetListItems>
        ';

        return sharepoint_calendar_helper::LoadEvents($query, $wsdl, $username, $password);
    }

    public static function GetUpcomingEvents($wsdl, $listName, $rowLimit='5', $username=SHAREPOINT_USER, $password=SHAREPOINT_USER_PW) {

        //XML for the request. Add extra fields as necessary
        $query = '
        <GetListItems xmlns="http://schemas.microsoft.com/sharepoint/soap/">
            <listName>' . $listName . '</listName>
            <query>
                <Query>
                    <Where>
                        <And>
                            <DateRangesOverlap>
								<FieldRef Name="EventDate" />
								<FieldRef Name="EndDate" />
								<FieldRef Name="RecurrenceID" />
								<Value Type="DateTime">
									<Year />
								</Value>
							</DateRangesOverlap>
                            <Eq>
                                <FieldRef Name="Category" />
                                <Value Type="Text">Public Event</Value>
                            </Eq>
                        </And>
                    </Where>
                </Query>
            </query>
            <queryOptions>
                    <QueryOptions>
                        <ExpandRecurrence>TRUE</ExpandRecurrence>
                        <CalendarDate><Today /></CalendarDate>
                    </QueryOptions>
            </queryOptions>
        </GetListItems>
        ';
	
	$upcomingEvents = sharepoint_calendar_helper::LoadEvents($query, $wsdl, $username, $password);
	
	if (is_array($upcomingEvents)) {
	    $upcomingEvents = array_filter(sharepoint_calendar_helper::LoadEvents($query, $wsdl, $username, $password), 'filterEvent');
            return array_slice($upcomingEvents, 0, $rowLimit);
	}
	else {
	    return null;
	}
    }

    public static function GetEventByID($eventID, $wsdl, $listName, $username=SHAREPOINT_USER, $password=SHAREPOINT_USER_PW) {
        //XML for the request. Add extra fields as necessary
        $query = '
        <GetListItems xmlns="http://schemas.microsoft.com/sharepoint/soap/">
            <listName>' . $listName . '</listName>
            <query>
                <Query>
                    <Where>
                        <DateRangesOverlap><FieldRef Name="EventDate" /><FieldRef Name="EndDate" /><FieldRef Name="RecurrenceID" /><Value Type="DateTime"><Month /></Value></DateRangesOverlap>
                        <Eq>
                          <FieldRef Name="ID" />
                          <Value Type="Text">{B07C58F1-485A-43A6-AC4F-C0077DD3F508}</Value>
                        </Eq>
                        
                    </Where>
                </Query>
            </query>
            <queryOptions>
                <QueryOptions>
                        <ExpandRecurrence>TRUE</ExpandRecurrence>
                        <CalendarDate><Today /></CalendarDate>
                </QueryOptions>
            </queryOptions>
            <viewFields>
                <ViewFields>
                    <FieldRef Name="GUID" />
                </ViewFields>
            </viewFields>
        </GetListItems>
        ';

        return sharepoint_calendar_helper::LoadEvents($query, $wsdl, $username, $password);
    }

    
    public static function LoadEvents($query, $wsdl, $username=SHAREPOINT_USER, $password=SHAREPOINT_USER_PW) {
        require_once TEMPLATEPATH. '/library/php/NuSoap/nusoap.php';

        //Basic authentication. Using UTF-8 to allow special characters.
        $client = new nusoap_client($wsdl, true);
        $client->setCredentials($username, $password, 'ntlm');
        $client->soap_defencoding = 'UTF-8';
        //Invoke the Web Service
        $result = $client->call('GetListItems', $query);
        
        //Error check
        if (isset($fault)) {
            echo("<h2>Error</h2>" . $fault);
        }
        //Extracting and preparing the Web Service response for display
        $data = substr($client->response, strpos($client->response, "<"), strlen($client->response) - 1);
        unset($client);
	
	
        try {
	    return sharepoint_calendar_helper::ExtractEventsFromXml($data);
	} catch (Exception $e) {
			
		if (function_exists('domain_mapping_siteurl')) 
			$domain = domain_mapping_siteurl(null);
		else
			$domain = get_bloginfo('url');
		
	    mail('seanj@ufl.edu', 'sharepoint cal error', $domain . ' : ' . $wsdl);
	    return null;
	}
        
    }

    public static function ExtractEventsFromXml($data) {
        if (empty($data)) 
            return;

        //Loading the XML result into parsable DOM elements
        $dom = new DOMDocument();
	
	
	    $dom->loadXML($data);
	    $results = $dom->getElementsByTagNameNS('#RowsetSchema', '*');



        $events = array();

        foreach ($results as $result) {
            $event = array(
                'id' => $result->getAttribute('ows_ID'),
                'title' => $result->getAttribute('ows_Title'),
                'start' => $result->getAttribute('ows_EventDate'),
				'end' => $result->getAttribute('ows_EndDate'),
                'allDay' => intval($result->getAttribute('ows_fAllDayEvent')),
                'description' => $result->getAttribute('ows_Description'),
                'location' => $result->getAttribute('ows_Location'),
                'category' => $result->getAttribute('Category'),
                'startTime' => date('g:ia', strtotime($result->getAttribute('ows_EventDate'))),
                'endTime' => date('g:ia', strtotime($result->getAttribute('ows_EndDate'))),
                'timestamp' => date('U', strtotime($result->getAttribute('ows_EventDate')))
                 
            );

            $end = $result->getAttribute('ows_EndDate');
            
            $recurring = $result->getAttribute('ows_fRecurrence');
            if (isset($end) && $recurring == '0' && date('n/j/Y', strtotime($event['start'])) != date('n/j/Y', strtotime($end))) {
                $event['end'] = $result->getAttribute('ows_EndDate');
            }


            array_push($events, $event);
        }

        return $events;
        
    }

    // converts the calendar url into wsdl address
    // this assumes the calendar format follows a specific pattern, e.g.  https://[domain]/[dept]/Lists/Calendar/Calendar.aspx
    public static function ConvertUrlToWSDL($calUrl) {
        $url = $calUrl;
        
        // if the url does not start with https://, add it
        if (!preg_match('/^https:\/\//i', $url)) {
            $url = 'https://' . preg_replace('/http:\/\//i', '', $url);
        }
        
        $url = preg_replace('/lists\/(.*)\/calendar.aspx/i', '_vti_bin/Lists.asmx?wsdl', $url);
        
        $url = str_replace(' ', '%20', $url);
        
        return $url;
    }

    
    public static function GetListNameFromUrl($calUrl) {
        preg_match('/lists\/(.*)\/calendar.aspx/i', $calUrl, $matches);
        return urldecode($matches[1]);
    }
    
    public static function EventDetailHtml() {
        return '<div id="eventDetails" style="display:none;">
                        <h4><div id="eventTitle"></div></h4>
                        <span id="eventLocation"></span><br />
                        <span id="eventDate"></span><br />
                        <span id="eventStartTime"></span><br />
                        <p><div id="eventDescription"></div></p>
			<a href="#" id="permalink" target="_blank">permalink</a>
                    </div>';
    }

    
    public static function CalendarJs($postID = null) {
        
        
        $js = ' <script type="text/javascript">
                        var deepLinked = false;
                        var linkedTimestamp;
                        var linkedDate;
			if (window.location.hash.length > 0 && window.location.hash != \'#none\') {
                            linkedDate = new Date(window.location.hash.split(\'|\')[1] * 1000);
                            linkedTimestamp = window.location.hash.split(\'|\')[1];
                        }
                         $(document).ready(function() {
							

                                $("#calendar").fullCalendar({ 
                                    header: {
                                                left: "prev,next today",
                                                center: "title",
                                                right: "month,agendaWeek,agendaDay"
                                            },
                                    year: linkedDate ? linkedDate.getFullYear() : new Date().getFullYear(),
                                    month: linkedDate ? linkedDate.getMonth() : new Date().getMonth(),
                                    loading: function(bool) {
                                                if (bool) {
                                                    $(".fc-content").fadeTo("fast", .4);
                                                    $("#overlay").fadeIn(200);
                                                }
                                                else {
                                                    $("#overlay").fadeOut(200);
                                                    $(".fc-content").fadeTo("fast", 1);
                                                }
                                        },
                                    eventSources: [
                                        {
                                            url: "'. get_bloginfo('template_directory') . '/library/php/sharepoint-calendar/events-feed.php",
                                            data: {' . (isset($postID) ? 'postID: "' . $postID . '"' : 'calendar: \'' . $_GET['c'] . '\'') . ' },
                                            complete: function() {
                                                if (window.location.hash.length > 0) {
                                                    var events = $("#calendar").fullCalendar("clientEvents", 
                                                        function(e) {
                                                            if (e.id == window.location.hash.split("|")[0].substring(1) && e.timestamp == linkedTimestamp) {
                                                                return true;
                                                            }
                                                        });
                                                    if (events.length > 0)
                                                        setEventDetails(events[0]);
                                                }
                                            }
                                        }
                                    ],
                                    eventClick: function(event) {
                                        setEventDetails(event);
                                        return false;
                                    }
                                });
                                
                                $(".fc-content").append(\'<div id="overlay"><img src="' . get_bloginfo('template_directory') . '/library/images/prettyPhoto/default/loader.gif" id="img-load"  /></div>\');

                                $("a[rel^=\'prettyPhoto\']").prettyPhoto( { 
				    deeplinking: false,
                                    social_tools:false,
                                    callback: function() {
					window.location.hash = \'#none\';
				    },
                                    markup: \'<div class="pp_pic_holder"> \
						<div class="ppt">&nbsp;</div> \
						<div class="pp_top"> \
							<div class="pp_left"></div> \
							<div class="pp_middle"></div> \
							<div class="pp_right"></div> \
						</div> \
						<div class="pp_content_container"> \
							<div class="pp_left"> \
							<div class="pp_right"> \
								<div class="pp_content"> \
									<div class="pp_loaderIcon"></div> \
									<div class="pp_fade"> \
										<a class="pp_close" href="#">Close</a> \
										<div class="pp_hoverContainer"> \
											<a class="pp_next" href="#">next</a> \
											<a class="pp_previous" href="#">previous</a> \
										</div> \
										<div id="pp_full_res"></div> \
										<div class="pp_details"> \
											<div class="pp_nav"> \
												<a href="#" class="pp_arrow_previous">Previous</a> \
												<p class="currentTextHolder">0/0</p> \
												<a href="#" class="pp_arrow_next">Next</a> \
											</div> \
											<p class="pp_description"></p> \
											{pp_social} \
                                                                                        <a href="#" class="pp_expand" title="Expand the image">Expand</a> \
										</div> \
									</div> \
								</div> \
							</div> \
							</div> \
						</div> \
						<div class="pp_bottom"> \
							<div class="pp_left"></div> \
							<div class="pp_middle"></div> \
							<div class="pp_right"></div> \
						</div> \
					</div> \
					<div class="pp_overlay"></div>\'
                                    
                                });
                         
                            });
                            
                       function formatDate(d) {
                            var d_names = new Array("Sunday", "Monday", "Tuesday",
                            "Wednesday", "Thursday", "Friday", "Saturday");

                            var m_names = new Array("January", "February", "March", 
                            "April", "May", "June", "July", "August", "September", 
                            "October", "November", "December");
                            var curr_day = d.getDay();
                            var curr_date = d.getDate();
                            var sup = "";
                            if (curr_date == 1 || curr_date == 21 || curr_date ==31)
                               {
                               sup = "st";
                               }
                            else if (curr_date == 2 || curr_date == 22)
                               {
                               sup = "nd";
                               }
                            else if (curr_date == 3 || curr_date == 23)
                               {
                               sup = "rd";
                               }
                            else
                               {
                               sup = "th";
                               }
                            var curr_month = d.getMonth();
                            var curr_year = d.getFullYear();

                            return d_names[curr_day] + " " + curr_date + "<SUP>"
                            + sup + "</SUP> " + m_names[curr_month] + " " + curr_year;  
                        }
                        
			function setEventDetails(event) {
			    $(\'#eventTitle\').html(event.title);
                                    $(\'#eventLocation\').html(event.location ? event.location : \'\');
                                    
                                    $(\'#eventDate\').html(formatDate(event.start));

                                    if (event.end)
                                        $(\'#eventDate\').append(\' to \' + formatDate(event.end))

                                    $(\'#eventStartTime\').html(event.startTime);
                                    if (event.endTime)
                                        $(\'#eventStartTime\').append(\' - \' + event.endTime)
                                    
                                    $(\'#eventDescription\').html(event.description ? event.description : \'\');
                                    window.location.hash = \'\' + event.id + \'|\' + event.timestamp;
				    $(\'#permalink\').attr(\'href\', window.location);
				    $(\'#eventOpen\').click();
			}
                            
                            
                      </script>';
        
        return $js;
    }
}


function filterEvent($event) {
    $today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
    return strtotime($event['start']) >= $today || (strtotime($event['start']) < $today && strtotime($event['end']) >= $today);
}
?>

<?php
$servername = "sql.mit.edu";
$username = "emmaliu";
$password = "mitswe19";
$dbname = "emmaliu+swe";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$kerberos = $_GET["kerberos"];
$eventName = $_GET["eventName"];
$password = trim($_GET["password"]);
$volunteerHours = $_GET["volunteerHours"];

$getEventInfoQuery = "SELECT Password, EventType, Attendees FROM Events WHERE Name = '" . $eventName . "'";
$eventInfo = mysqli_query($conn, $getEventInfoQuery);
$eventInfoRow = mysqli_fetch_assoc($eventInfo);
$eventPassword = $eventInfoRow['Password'];
$eventType = $eventInfoRow['EventType'];
#$eventAttendees = $eventInfoRow['Attendees'];

if ($password != $eventPassword) {
    echo "Password entered is incorrect";
} else {
    if ($eventType == "General") {
        $generalEventsInfoQuery = "SELECT NumGeneralEventsAttended, GeneralEventsAttended FROM Members WHERE Kerberos = '" . $kerberos . "'";
        $generalEventsInfo = mysqli_query($conn, $generalEventsInfoQuery);
        $generalEventsRow = mysqli_fetch_assoc($generalEventsInfo);
        $generalEventsNum = $generalEventsRow['NumGeneralEventsAttended'];
        $generalEventsAttended = $generalEventsRow['GeneralEventsAttended'];

        $generalEventsNum = $generalEventsNum + 1;

        #check if event attendance has already been logged
        $attendedEvents = explode(", ", $generalEventsAttended);
        $eventAlreadyAttended = FALSE;

        foreach ($attendedEvents as $event) {
            if ($event == $eventName) {
                $eventAlreadyAttended = TRUE;
                break;
            }
        }
        unset($event);

        if ($eventAlreadyAttended) {
            echo "You have already logged attendance for this event";
        } else {
            if ($generalEventsNum == 1) { #no events previously attended
                $generalEventsAttended = $eventName;
            } else {
                $generalEventsAttended = $generalEventsAttended . ", " . $eventName;
            }
    
            #add new event attendee
            // if (isset($eventAttendees) && $eventAttendees != '') {
            //     $eventAttendees = $eventAttendees . ", " . $kerberos;
            // } else {
            //     $eventAttendees = $kerberos;
            // }
    
            #$sql = "UPDATE Members SET NumGeneralEventsAttended=$generalEventsNum, GeneralEventsAttended= '" . $generalEventsAttended . "' WHERE Kerberos= '" . $kerberos . "'; UPDATE Events SET Attendees= '" . $eventAttendees . "' WHERE Name = '" . $eventName . "'";
    
            $sql = "UPDATE Members SET NumGeneralEventsAttended=$generalEventsNum, GeneralEventsAttended= '" . $generalEventsAttended . "' WHERE Kerberos= '" . $kerberos . "'";
        }

    } else if ($eventType == "BoardMeeting") {
        $boardMeetingsInfoQuery = "SELECT IsBoardMember, NumBoardMeetingsAttended, BoardMeetingsAttended FROM Members WHERE Kerberos = '" . $kerberos . "'";
        $boardMeetingsInfo = mysqli_query($conn, $boardMeetingsInfoQuery);
        $boardMeetingsRow = mysqli_fetch_assoc($boardMeetingsInfo);
        $isBoardMember = $boardMeetingsRow['IsBoardMember'];
        $boardMeetingsNum = $boardMeetingsRow['NumGeneralEventsAttended'];
        $boardMeetingsAttended = $boardMeetingsRow['GeneralEventsAttended'];

        #check if attendance has already been logged for this event
        $attendedEvents = explode(", ", $boardMeetingsAttended);
        $eventAlreadyAttended = FALSE;

        foreach ($attendedEvents as $event) {
            if ($event == $eventName) {
                $eventAlreadyAttended = TRUE;
                break;
            }
        }
        unset($event);

        if ($eventAlreadyAttended) {
             echo "You have already logged attendance for this event";
        } else {
            $boardMeetingsNum = $boardMeetingsNum + 1;
        
            if ($boardMeetingsNum == 1) { #no events previously attended
                $boardMeetingsAttended = $eventName;
            } else {
                $boardMeetingsAttended = $boardMeetingsAttended . ", " . $eventName;
            }

            // #add new event attendee
            // if (isset($eventAttendees) && $eventAttendees != '') {
            //     $eventAttendees = $eventAttendees . ", " . $kerberos;
            // } else {
            //     $eventAttendees = $kerberos;
            // }
    
            #$sql = "UPDATE Members SET NumBoardMeetingsAttended=$boardMeetingsNum, BoardMeetingsAttended= '" . $boardMeetingsAttended . "' WHERE Kerberos= '" . $kerberos . "'; UPDATE Events SET Attendees= '" . $eventAttendees . "' WHERE Name = '" . $eventName . "'";

            $sql = "UPDATE Members SET NumBoardMeetingsAttended=$boardMeetingsNum, BoardMeetingsAttended= '" . $boardMeetingsAttended . "' WHERE Kerberos= '" . $kerberos . "'";
            
        }

    } else if ($eventType == "Outreach") {
        $outreachInfoQuery = "SELECT NumOutreachVolunteeringHours, OutreachEventsAttended FROM Members WHERE Kerberos = '" . $kerberos . "'";
        $outreachInfo = mysqli_query($conn, $outreachInfoQuery);
        $outreachRow = mysqli_fetch_assoc($outreachInfo);
        $outreachHoursNum = $outreachRow['NumOutreachVolunteeringHours'];
        $outreachEventsAttended = $outreachRow['OutreachEventsAttended'];

        #check if event attendance has already been logged
        $attendedEvents = explode(", ", $outreachEventsAttended);
        $eventAlreadyAttended = FALSE;

        foreach ($attendedEvents as $event) {
            if ($event == $eventName) {
                $eventAlreadyAttended = TRUE;
                break;
            }
        }
        unset($event);

        if ($eventAlreadyAttended) {
            echo "You have already logged volunteer hours for this event";
        } else {
            if ($outreachHoursNum == 0) { #no previous volunteer hours
                $outreachEventsAttended = $eventName;
            } else {
                $outreachEventsAttended = $outreachEventsAttended . ", " . $eventName;
            }
    
            $outreachHoursNum = $outreachHoursNum + $volunteerHours;
    
            // #add new event attendee
            // if (isset($eventAttendees) && $eventAttendees != '') {
            //     $eventAttendees = $eventAttendees . ", " . $kerberos;
            // } else {
            //     $eventAttendees = $kerberos;
            // }
    
            #$sql = "UPDATE Members SET NumOutreachVolunteeringHours=$outreachHoursNum, OutreachEventsAttended= '" . $outreachEventsAttended . "' WHERE Kerberos= '" . $kerberos . "'; UPDATE Events SET Attendees= '" . $eventAttendees . "' WHERE Name = '" . $eventName . "'";
    
            $sql = "UPDATE Members SET NumOutreachVolunteeringHours=$outreachHoursNum, OutreachEventsAttended= '" . $outreachEventsAttended . "' WHERE Kerberos= '" . $kerberos . "'";
        }
        
    } else { //$eventType == "Recruiting"
        if (isset($volunteerHours)) { //board member logging volunteer hours
            $recruitingEventsInfoQuery = "SELECT IsBoardMember, NumRecruitingEventsHours, RecruitingEventsAttended FROM Members WHERE Kerberos = '" . $kerberos . "'";
            $recruitingEventsInfo = mysqli_query($conn, $recruitingEventsInfoQuery);
            $recruitingEventsRow = mysqli_fetch_assoc($recruitingEventsInfo);
            $isBoardMember = $recruitingEventsRow['IsBoardMember'];
            $recruitingEventsHoursNum = $recruitingEventsRow['NumRecruitingEventsHours'];
            $recruitingEventsAttended = $recruitingEventsRow['RecruitingEventsAttended'];

            #check if event attendance has already been logged
            $attendedEvents = explode(", ", $recruitingEventsAttended);
            $eventAlreadyAttended = FALSE;

            foreach ($attendedEvents as $event) {
                if ($event == $eventName) {
                    $eventAlreadyAttended = TRUE;
                    break;
                }
            }
            unset($event);

            if ($eventAlreadyAttended) {
                echo "You have already logged volunteer hours for this event";
            } else {
                if ($isBoardMember == 0) {
                    echo "Only board members can log volunteer hours for recruiting events";
                } else {
                    if ($recruitingEventsHoursNum == 0) { #no previous volunteer hours
                        $recruitingEventsAttended = $eventName;
                    } else {
                        $recruitingEventsAttended = $recruitingEventsAttended . ", " . $eventName;
                    }
            
                    $recruitingEventsHoursNum = $recruitingEventsHoursNum + $volunteerHours;
            
                    $sql = "UPDATE Members SET NumRecruitingEventsHours=$recruitingEventsHoursNum, RecruitingEventsAttended= '" . $recruitingEventsAttended . "' WHERE Kerberos= '" . $kerberos . "'";
                }
            }

        } else { //general member logging attendance
            $generalEventsInfoQuery = "SELECT IsBoardMember, NumGeneralEventsAttended, GeneralEventsAttended FROM Members WHERE Kerberos = '" . $kerberos . "'";
            $generalEventsInfo = mysqli_query($conn, $generalEventsInfoQuery);
            $generalEventsRow = mysqli_fetch_assoc($generalEventsInfo);
            $isBoardMember = $generalEventsRow['IsBoardMember'];
            $generalEventsNum = $generalEventsRow['NumGeneralEventsAttended'];
            $generalEventsAttended = $generalEventsRow['GeneralEventsAttended'];

            #check if event attendance has already been logged
            $attendedEvents = explode(", ", $generalEventsAttended);
            $eventAlreadyAttended = FALSE;

            foreach ($attendedEvents as $event) {
                if ($event == $eventName) {
                    $eventAlreadyAttended = TRUE;
                    break;
                }
            }
            unset($event);

            if ($eventAlreadyAttended) {
                echo "You have already logged attendance for this event";
            } else {
                if ($isBoardMember == 1) {
                    echo "Board members cannot log event attendance for recruiting events";
                } else {
                    $generalEventsNum = $generalEventsNum + 1;
                
                    if ($generalEventsNum == 1) { #no events previously attended
                        $generalEventsAttended = $eventName;
                    } else {
                        $generalEventsAttended = $generalEventsAttended . ", " . $eventName;
                    }
    
                    #add new event attendee
                    // if (isset($eventAttendees) && $eventAttendees != '') {
                    //     $eventAttendees = $eventAttendees . ", " . $kerberos;
                    // } else {
                    //     $eventAttendees = $kerberos;
                    // }
        
                    #$sql = "UPDATE Members SET NumGeneralEventsAttended=$generalEventsNum, GeneralEventsAttended= '" . $generalEventsAttended . "' WHERE Kerberos= '" . $kerberos . "'; UPDATE Events SET Attendees= '" . $eventAttendees . "' WHERE Name = '" . $eventName . "'";
    
                    $sql = "UPDATE Members SET NumGeneralEventsAttended=$generalEventsNum, GeneralEventsAttended= '" . $generalEventsAttended . "' WHERE Kerberos= '" . $kerberos . "'";
                }
            }
        }
    }

    if (isset($sql) && $sql !== '') {
        if ($conn->multi_query($sql) === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    
}

$conn->close();
?>
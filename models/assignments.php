<?php
require_once 'dbs.php';
include_once '../models/courses.php';

class assignments {

	public $setgrade;
	function __construct() {

		$this->setGrade=0;
		$dbs = New dbs();

		$conn = $dbs ->connect();

	}
	public function getAssignmentDetails($assignid)
	{
		$query="select assignid, assign_name, courseid, assignno, filepath, deadline, maxmarks from assignments where assignid='".$assignid."';";
		$result = mysql_query($query);
		return mysql_fetch_array($result);
	}

	public function checkWithinMax($assignid,$marks)
	{
		$query="select * from assignments where assignid='".$assignid."';";
		$result=mysql_query($query);
		if($result)
		{
			$row = mysql_fetch_array($result);
			$max = $row['maxmarks'];
			if($marks<=$max)
				return 1;
			else
				return 0;
		}

		echo mysql_error();
		return -1;
	}

	
	public function getAssignmentSubmissions($assignid)
	{
		$query="select subid, studid, filepath, stime, marks from assignsubmissions where assignid='".$assignid."';";
		$result = mysql_query($query);
		return $result;
	}

	public function getAssignmentId($subid)
	{
		$query = "select assignid from assignsubmissions where subid='".$subid."';";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		return $row['assignid'];
	}

	public function getAssignmentLink($subid)
	{
		$query = "select filepath from assignsubmissions where subid='".$subid."';";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		return $row['filepath'];
	}

	public function setMarks($subid,$marks)
	{
		$query = "update ".DBNAME.".".SUBMS_TBL." SET marks='".$marks."' WHERE subid='".$subid."';";
		$result = mysql_query($query);
		if(!$result) {
    		die("Database query failed: " . mysql_error());
	 	}
		return $result;
	}


	public function setGrade($courseid, $userid, $marks)
	{
		$query = "update coursestudregistration SET grade='".$marks."' WHERE courseid='".$courseid."' && studentid='".$userid."';";
		$result = mysql_query($query);
		   echo "Add query failed: " . mysql_error();

		if(!$result) {
    		echo "Add query failed: " . mysql_error();
	 	}
	 	$this->setGrade=1;
		return $result;
	}


	public function checkGrade($courseid, $userid)
	{
		$query = "select * from coursestudregistration WHERE courseid='".$courseid."' && studentid='".$userid."';";
		$result = mysql_query($query);
		echo mysql_error();
		if($result)
		{
			if($row=mysql_fetch_array($result)) 
			{
	    		if(!is_null($row['grade']))
	    			return 1;
	    		return 0;
		 	}
		}
		
	 	
		return 0;
	}


	public function getGrade($courseid, $userid)
	{

		$query = "select grade from coursestudregistration WHERE courseid='".$courseid."' && studentid='".$userid."';";
		
		$result = mysql_query($query);
		echo mysql_error();

		if($result)
		{			
			$row=mysql_fetch_array($result);
			
			
			if($row) 
			{			
	    		if(!is_null($row['grade']))
	    		{
	    			
	    			return $row['grade'];
	    		}
	    		echo mysql_error();
	    		{
	    			return 0;
	    		}
		 	}

		
	 		    	

		return 0;
	}

	}



	public function getMarks($subid)
	{
		$query = 'select marks from assignsubmissions where subid='.$subid.';';
		$result = mysql_query($query);
		
		if(!$result) {
    		die("Database query failed: " . mysql_error());
	 	}
	 	$row = mysql_fetch_array($result);

		return $row['marks'];
	}


	public function getStudMarks($userid, $assid)
	{
		$query = 'select marks from assignsubmissions where studid='.$userid.' && assignid='.$assid.';';
		$result = mysql_query($query);
		//echo $result;
		if(!$result) {
    			return -1;
	 	}
	 	$row = mysql_fetch_array($result);
	 	//echo $row['marks'];
		return $row['marks'];
	}

	public function getCourseidfromassgn($assignid)
	{
		$query = "select courseid from assignments where assignid='".$assignid."';";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		return $row['courseid'];
	}

	public function getAssignmentNo($assignid)
	{
		$query = "select assignno from assignments where assignid='".$assignid."';";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		return $row['assignno'];
	}

	public function getAssignmentidsSubmittedCourse($studid, $courseid)
	{
		$query = "select distinct a.assignid FROM assignments as a, assignsubmissions as b where a.assignid=b.assignid and b.studid='".$studid."' and a.courseid='".$courseid."';";
		$result = mysql_query($query);
		return $result;
	}

	public function getSubmissionDetails($assignid, $userid)
	{
		//echo $assignid; echo $userid;
		$query = "select filepath,stime,marks from assignsubmissions where studid='".$userid."' and assignid='".$assignid."';";
		$result = mysql_query($query);

		//echo mysql_error();
	
		$row = mysql_fetch_array($result);
		//echo $row['filepath'];
		//echo $row['stime'];
		return $row;
	}

	public function addAssignNotifs($assnid)
	{
		$courseid= $this->getCourseidfromassgn($assnid);

		$course= New courses();

		$results= $course->getCourseStudents($courseid);

		while($row= mysql_fetch_array($results))
		{
			print_r ($row);
			echo 'entered';
			$query = "insert into notifsassignments (notifid, foruserid, assignments, seen) values(DEFAULT, '".$row['userid']."', '".$courseid."','0');";

			$output= mysql_query($query);

			if($output)
			{	
				echo "added notifs";
			}

		}

				return 1;

	} 

}

?>
<?php
/**
 * Access from index.php:
 */
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

class Videos_Controller extends ZP_Load {
	
	public function __construct() {
		$this->Templates = $this->core("Templates");
		
		$this->config("videos");

		$this->helper("pagination");
		
		$this->application = $this->app("videos");
		
		$this->Templates->theme();
	}
	
	public function index() {
		$this->videos();
	}

	public function rss() {
		$this->helper("time");
		$this->Videos_Model = $this->model("Videos_Model");
		$data = $this->Videos_Model->getRSS();
		
		if ($data) {
			$vars["videos"]= $data;	

			$this->view("rss", $vars, $this->application);
		} else {
			redirect();
		}

	}
	
	public function videos() {
		$this->CSS("videos", $this->application);
		$this->CSS("prettyPhoto", $this->application);
		$this->CSS("pagination");
		
		$this->Videos_Model = $this->model("Videos_Model");
		
		$limit = $this->limit();
	
		$videos = $this->Videos_Model->getVideos($limit);	
				
		if ($videos) {			
			$vars["pagination"] = $this->pagination;
			$vars["videos"] 	= $videos;			
			$vars["view"] 		= $this->view("videos", true);
			
			$this->render("content", $vars);
		} else {
			redirect();
		}
	}
	
	public function video($id){
		$this->Videos_Model = $this->model("Videos_Model");
		$video = $this->Videos_Model->getByID($id);
		if ($video){
			$vars["video"] = $video[0];
			$vars["view"]  = $this->view("video", true);
			
			$this->render("content", $vars);
		} else{
			redirect();
		}
	}
	
	private function limit() { 			
		$start = (segment(0, isLang()) === "videos" and segment(1, isLang()) > 0) ? (segment(1, isLang()) * MAX_LIMITVideos) - MAX_LIMITVideos : 0;
		
		$limit = $start .", ". MAX_LIMITVideos;			
		$count = $this->Videos_Model->count();
		$URL   = path("videos/");			
		
		$this->pagination = ($count > MAX_LIMITVideos) ? paginate($count, MAX_LIMITVideos, $start, $URL) : null;	

		return $limit;
	}
	
}

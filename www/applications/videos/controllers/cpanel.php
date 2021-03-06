<?php
/**
 * Access from index.php:
 */
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

class CPanel_Controller extends ZP_Load
{

	private $vars = array();

	public function __construct()
	{
		$this->app("cpanel");

		$this->application = whichApplication();

		$this->CPanel = $this->classes("cpanel", "CPanel", null, "cpanel");

		$this->isAdmin = $this->CPanel->load();

		$this->vars = $this->CPanel->notifications();

		$this->helper("pagination");

		$this->CPanel_Model = $this->model("CPanel_Model");

		$this->Templates = $this->core("Templates");

		$this->Templates->theme("cpanel");
	}

	public function index()
	{
		if ($this->isAdmin) {
			redirect("cpanel");
		} else {
			$this->login();
		}
	}

	public function check()
	{
		if (POST("trash") and is_array(POST("records"))) { 
			foreach (POST("records") as $record) {
				$this->trash($record, true); 
			}

			redirect("$this->application/cpanel/results");
		} elseif (POST("restore") and is_array(POST("records"))) {
			foreach (POST("records") as $record) {
				$this->restore($record, true); 
			}

			redirect("$this->application/cpanel/results");
		} elseif (POST("delete") and is_array(POST("records"))) {
			foreach (POST("records") as $record) {
				$this->delete($record, true); 
			}

			redirect("$this->application/cpanel/results");
		}

		return false;
	}

	public function delete($ID = 0, $return = false)
	{
		if (!$this->isAdmin) {
			$this->login();
		}

		if ($this->CPanel_Model->delete($ID)) {
			if ($return) {
				return true;
			}

			redirect("$this->application/cpanel/results/trash");
		} else {
			if ($return) {
				return false;
			}

			redirect("$this->application/cpanel/results");
		}
	}

	public function restore($ID = 0, $return = false)
	{ 
		if (!$this->isAdmin) {
			$this->login();
		}

		if ($this->CPanel_Model->restore($ID)) {
			if ($return) {
				return true;
			}

			redirect("$this->application/cpanel/results/trash");
		} else {
			if ($return) {
				return false;
			}

			redirect("$this->application/cpanel/results");
		}
	}

	public function trash($ID = 0, $return = false)
	{
		if (!$this->isAdmin) {
			$this->login();
		}

		if ($this->CPanel_Model->trash($ID)) {
			if ($return) {
				return true;
			}	

			redirect("$this->application/cpanel/results");
		} else {
			if ($return) {
				return false;
			}

			redirect("$this->application/cpanel/add");
		}
	}

	public function add()
	{
		if (!$this->isAdmin) {
			$this->login();
		}

		$this->title("Add");

		$this->js("ajax/search", $this->application);
		$this->js("custom-actions", $this->application);

		$this->CSS("forms", "cpanel");

		$this->config("videos", $this->application);

		$this->Youtube = $this->library("youtube", "Youtube", array(), $this->application);

		$Model = ucfirst($this->application) . "_Model";
		$this->$Model = $this->model($Model);

		$this->vars["videos"] = $this->$Model->getByUser(USER_YOUTUBE);
		$this->vars["alert"] = false;

		if (POST("save")) {
			$this->vars["alert"] = $this->$Model->cpanel("save");
		} elseif (POST("cancel")) {
			redirect("cpanel");
		}

		$this->vars["href"] = path($this->application ."/cpanel/add");
		$this->vars["view"] = $this->view("add", true, $this->application);
		
		$this->render("content", $this->vars);
	}

	public function edit($ID = 0)
	{
		if (!$this->isAdmin) {
			$this->login();
		}

		if ((int) $ID === 0) { 
			redirect($this->application ."/cpanel/results");
		}

		$this->title("Edit");

		$this->CSS("forms", "cpanel");
		$this->CSS("categories", "categories");

		$Model = ucfirst($this->application) ."_Model";
		$this->$Model = $this->model($Model);

		if (POST("edit")) {
			$this->vars["alert"] = $this->$Model->cpanel("edit");
		} elseif (POST("cancel")) {
			redirect("cpanel");
		} 

		$data = $this->$Model->getByID($ID);

		if ($data) {
			$this->Categories = $this->classes("Categories", "categories");

			$this->vars["data"] = $data;
			$this->vars["href"] = path($this->application ."/cpanel/edit/$ID");
			$this->vars["view"] = $this->view("add", true, $this->application);

			$this->render("content", $this->vars);
		} else {
			redirect($this->application ."/cpanel/results");
		}
	}

	public function login()
	{
		$this->title("Login");
		$this->CSS("login", "users");

		if (POST("connect")) {
			$this->Users_Controller = $this->controller("Users_Controller");

			$this->Users_Controller->login("cpanel");
		} else {
			$this->vars["URL"] = getURL();
			$this->vars["view"] = $this->view("login", true, "cpanel");
		}

		$this->render("include", $this->vars);
		$this->rendering("header", "footer");

		exit;
	}

	public function results()
	{
		if (!$this->isAdmin) {
			$this->login();
		}

		$this->check();

		$this->title("Manage ". $this->application);
		$this->CSS("results", "cpanel");
		$this->CSS("pagination");

		$this->js("checkbox");
		$this->js("js/pretty-photo", $this->application);
		$this->js("js/videos-lightbox", $this->application);

		$trash = (segment(3, isLang()) === "trash") ? true : false;

		$total = $this->CPanel_Model->total($trash);
		$thead = $this->CPanel_Model->thead("checkbox, ". getFields($this->application) .", Action", false);
		$pagination = $this->CPanel_Model->getPagination($trash);
		$tFoot = getTFoot($trash);
		
		$this->vars["message"] = (!$tFoot) ? "Error" : null;
		$this->vars["pagination"] = $pagination;
		$this->vars["trash"] = $trash;
		$this->vars["search"] = getSearch(); 
		$this->vars["table"] = getTable(__("Manage ". ucfirst($this->application)), $thead, $tFoot, $total);
		$this->vars["view"] = $this->view("results", true, "cpanel");

		$this->render("content", $this->vars);
	}
	
}

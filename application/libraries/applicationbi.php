<?php

class ApplicationBi{
	
	public function getApplications($params){
		$CI =& get_instance();
		
		$CI->load->model("applicationdao");
		
		
		$apps = $CI->applicationdao->getApplications($params);
		
		$tree = $this->buildTree($apps,"menu");
		
		return $tree->getRoot();

	}
	
	public function getTree(){
		$CI =& get_instance();
		$CI->load->model("applicationdao");
		
		$apps = $CI->applicationdao->getAll();
		$tree = $this->buildTree($apps,"children");
		
		return $tree->getRoot();
	}
	
	private function buildTree($apps,$text){
		$CI =& get_instance();
		$CI->load->library("tree");
		
		$temp = array();
		foreach($apps as $app){
			$iconCls = "";
			if($app["configurations"]){
				$conf = json_decode($app["configurations"]);
				if($conf){
					if(property_exists($conf,"iconCls")){
						$iconCls = $conf->iconCls;
					}
				}
			}
			array_push($temp,array(
				"text"			=> $app["name"],
				"name"			=> $app["name"],
				"application_k"	=> $app["application_k"],
				"application_parent_k"=> $app["application_parent_k"],
				"class"			=> $app["class"],
				"description"	=> $app["description"],
				"configurations"=> $app["configurations"],
				"active"		=> $app["active"],
				"iconCls"		=> $iconCls
			));
		}
		
		// Creating the Tree
		$tree = new Tree();
		$tree->setChildProperty($text);
		$tree->setIdProperty("application_k");
		foreach($temp as $app){
			$tree->addChild($app,$app["application_parent_k"]);
		}
		return $tree;
	}
}
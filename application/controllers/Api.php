<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Kolkata");
require_once APPPATH.'libraries/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

class Api extends CI_Controller {
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		// header('Content-Type: application/json');
		parent::__construct();
		$this->load->model('adminmodel');
		$this->load->library('pdf');
	}
	
	public function homeimage()
	{
		die;
		$data['status']=1;
		$data['message']="Home Image";
		$homeimage=$this->adminmodel->getfulldata("homeimage");
		for ($i = 0; $i < count($homeimage); $i++) {	
			$data["homeimage"][$i]['image']		=	base_url().'./images/'.$homeimage[$i]['image'];
		}
		echo json_encode($data);
	}
	
	public function login()
	{
		die;
		$this->form_validation->set_rules("username","User Name",'trim|required');
		$this->form_validation->set_rules("email","Email",'trim|required|valid_email');
		if($this->form_validation->run()==FALSE){
			$data['status']=0;
			$data['message']="Wrong format";
			$data['userid']=0;
		}
		else{
			$username=$this->input->post("username");
			$email=$this->input->post("email");			
			$result=$this->adminmodel->login($username,$email);
			if($result){						
				$userid=$result['id'];							
				$data['status']=1;
				$data['message']="Logged successfully";
				$data['userid']=$userid;
			}
			else{
				$insertdata=array(
				"name" => $this->input->post("username"),
				"email" => $this->input->post("email"),
				"createddate" => date("Y-m-d h:i:s")
				);
				$statusvalue=$this->adminmodel->insertdata("users",$insertdata);
				if($statusvalue >0 )
					$data['status']=1;
				else
					$data['status']=0;
				
				$data['message']="Inserted successfully";
				$data['userid']=$statusvalue;
				
			}
		}
		
		echo json_encode($data);
	}	
	
	public function homepage()
	{
		die;
		$pageid="1";
		$pagename=$this->adminmodel->getpageid_api("pages",$pageid);
				
		$data=array();		
		$data['status']=1;
		
		$data["title"]=$pagename['title'];
		
		// $data["leftsidebar"][0]['content']="Home";
		// $data["leftsidebar"][1]['content']="About";
		// $data["leftsidebar"][2]['content']="Help";
		// $data["leftsidebar"][3]['content']="T&C's";
		// $data["leftsidebar"][4]['content']="Contact us";
		// $data["leftsidebar"][5]['content']="Profile";
				
		$topbanner=$this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']=$pageid;
		}
		
		// $bottombanner=$this->adminmodel->getfulldata_api("banner",'bottom',$pageid);
		// for($i=0; $i < count($bottombanner); $i++){			
		// 	$data['bottombanner'][$i]['image']=base_url().'./images/'.$bottombanner[$i]['image'];
		// 	$data['bottombanner'][$i]['link']=$bottombanner[$i]['link'];
		// 	$data['bottombanner'][$i]['imageid']=$bottombanner[$i]['id'];
		// 	$data['bottombanner'][$i]['pageid']=$pageid;
		// }
		
		// $backbanner=$this->adminmodel->getfulldata("backbanner");
		// for ($i = 0; $i < count($backbanner); $i++) {	
		// 	$data["backgroundbanner"][$i]['image']=base_url().'./images/'.$backbanner[$i]['image'];
		// }
		
		$scrollingtickerline=$this->adminmodel->getfulldata_scrolling_api("scrollingticker",$pageid);		
		for($i=0; $i < count($scrollingtickerline); $i++){			
			$data["scrollingticker"][$i]['content']=$scrollingtickerline[$i]['scrollingticker'];
		}
				
		// $plumbingimage=$this->adminmodel->getfulldata("plumbingafrica");	
		// for ($i = 0; $i < count($plumbingimage); $i++) {	
		// 	$data["plumbingafrica"][$i]['image']=base_url().'./images/'.$plumbingimage[$i]['image'];
		// }		
		
		// $news=$this->adminmodel->getfulldata("news");
		// for ($i = 0; $i < count($news); $i++) {			
		// 	$data["news"][$i]['id']=$news[$i]['id'];
		// 	$data["news"][$i]['title']=$news[$i]['title'];
		// 	$data["news"][$i]['description']=$news[$i]['description'];
		// 	$data["news"][$i]['detail']=$news[$i]['detail'];
		// 	$data["news"][$i]['createddate']=$news[$i]['createddate'];
		// 	$data["news"][$i]['image']=base_url().'./images/'.$news[$i]['file'];
		// }				
			
		$data["centericon"][0]['image']=base_url().'./appicons/iops_new1.png';	
		$data["centericon"][0]['title']="IOPSA";
		$data["centericon"][0]['pagelink']="IOPSAHomePage.html";
		$data["centericon"][1]['image']=base_url().'./appicons/auditit1.png';	
		$data["centericon"][1]['title']="Audit IT";
		$data["centericon"][1]['pagelink']="ComingSoon.html";
		$data["centericon"][2]['image']=base_url().'./appicons/pirb1.png';	
		$data["centericon"][2]['title']="PIRB";
		$data["centericon"][2]['pagelink']="PIRBHome.html";
		$data["centericon"][3]['image']=base_url().'./appicons/magzinenew1.png';	
		$data["centericon"][3]['title']="Magazine";
		$data["centericon"][3]['pagelink']="Magazine.html";
		$data["centericon"][4]['image']=base_url().'./appicons/podcast1.png';	
		$data["centericon"][4]['title']="Podcast";
		$data["centericon"][4]['pagelink']="podcast.html";
		$data["centericon"][5]['image']=base_url().'./appicons/installionguide1.png';	
		$data["centericon"][5]['title']="Installation Guides";
		$data["centericon"][5]['pagelink']="ProductGuide.html";
		$data["centericon"][6]['image']=base_url().'./appicons/cpd1.png';	
		$data["centericon"][6]['title']="CPD Activities";
		$data["centericon"][6]['pagelink']="Homecpd.html";
		$data["centericon"][7]['image']=base_url().'./appicons/finder_new1.png';	
		$data["centericon"][7]['title']="Findar";
		$data["centericon"][7]['pagelink']="ComingSoon.html";
		$data["centericon"][8]['image']=base_url().'./appicons/saw_new1.png';	
		$data["centericon"][8]['title']="SAW";
		$data["centericon"][8]['pagelink']="Saw.html";
		$data["centericon"][9]['image']=base_url().'./appicons/tools1.png';	
		$data["centericon"][9]['title']="Tools";
		$data["centericon"][9]['pagelink']="ToolsPage.html";
		$data["centericon"][10]['image']=base_url().'./appicons/heathsafety1.png';	
		$data["centericon"][10]['title']="Health & Safety";
		$data["centericon"][10]['pagelink']="HealthSafety.html";
		$data["centericon"][11]['image']=base_url().'./appicons/buysell1.png';	
		$data["centericon"][11]['title']="Buy & Sell";
		$data["centericon"][11]['pagelink']="BuyAndSellHome.html";
		$data["centericon"][12]['image']=base_url().'./appicons/contact1.png';	
		$data["centericon"][12]['title']="Contact Us";
		$data["centericon"][12]['pagelink']="HomeContactus.html";
		// $data["centericon"][13]['image']=base_url().'./appicons/blue.jpg';	
		// $data["centericon"][13]['title']="My Profile";
		// $data["centericon"][13]['pagelink']="Profile.html";
		// $data["centericon"][14]['image']=base_url().'./appicons/blue.jpg';	
		// $data["centericon"][14]['title']="Rate My Work";
		// $data["centericon"][14]['pagelink']="rate_my_work.html";
		
		// $data["bottomicon"][0]['image']=base_url().'./appicons/ico13.png';	
		// $data["bottomicon"][0]['title']="MANAGE IT";
		// $data["bottomicon"][1]['image']=base_url().'./appicons/icons22.png';	
		// $data["bottomicon"][1]['title']="PLUMBING CODE";
		// $data["bottomicon"][2]['image']=base_url().'./appicons/icons22.png';	
		// $data["bottomicon"][2]['title']="PLACE HOLDER";
		// $data["bottomicon"][3]['image']=base_url().'./appicons/icons22.png';	
		// $data["bottomicon"][3]['title']="PLACE HOLDER";		
				
		echo json_encode($data);		
		//echo '<pre>'; print_r($data);
	}
	public function homepage_magazine()
	{
		die;
		// $pageid="43";
		$pageid = $this->config->item('pagesid')['42'];
		$pagename = $this->adminmodel->getpageid_api("pages",$pageid);
				
		$data=array();		
		$data['status']=1;
		
		$data["title"]=$pagename['title'];
		
		$topbanner=$this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']=$pageid;
		}
		
		$scrollingtickerline=$this->adminmodel->getfulldata_scrolling_api("scrollingticker","1");		
		for($i=0; $i < count($scrollingtickerline); $i++){			
			$data["scrollingticker"][$i]['content']=$scrollingtickerline[$i]['scrollingticker'];
		}
			
		
		$news=$this->adminmodel->getfulldata("news");
		for ($i = 0; $i < count($news); $i++) {			
			$data["news"][$i]['id'] = $news[$i]['id'];
			$data["news"][$i]['title'] = $news[$i]['title'];
			$data["news"][$i]['description'] = $news[$i]['description'];
			$data["news"][$i]['detail'] = $news[$i]['detail'];
			$data["news"][$i]['createddate'] = $news[$i]['createddate'];
			// $data["news"][$i]['image']=base_url().'./images/'.$news[$i]['image'];
			$data["news"][$i]['type'] = $news[$i]['type'];
			$data["news"][$i]['type_word'] = $this->config->item('magazinetype')[$news[$i]['type']];
			if ($news[$i]['file'] !='') {
				$data["news"][$i]['image'] = base_url().'./images/'.$news[$i]['file'];
			}else{
				$data["news"][$i]['image'] = NULL;
			}
			if ($news[$i]['feat_file'] !='') {
				$data["news"][$i]['feat_file'] = base_url().'./images/'.$news[$i]['feat_file'];
			}else{
				$data["news"][$i]['feat_file'] = NULL;
			}
			
		}				
			
				
		echo json_encode($data);		
		//echo '<pre>'; print_r($data);
	}
	public function homepage_podcost()
	{
		die;
		$pageid="56";
		$pagename=$this->adminmodel->getpageid_api("pages",$pageid);
				
		$data=array();		
		$data['status']=1;
		
		$data["title"]=$pagename['title'];
		
		$topbanner=$this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']=$pageid;
		}
		
		$scrollingtickerline=$this->adminmodel->getfulldata_scrolling_api("scrollingticker","1");		
		for($i=0; $i < count($scrollingtickerline); $i++){			
			$data["scrollingticker"][$i]['content']=$scrollingtickerline[$i]['scrollingticker'];
		}
			
				
		echo json_encode($data);		
		//echo '<pre>'; print_r($data);
	}
	public function homepage_cpd()
	{
		die;
		$pageid="24";
		$pagename=$this->adminmodel->getpageid_api("pages",$pageid);
				
		$data=array();		
		$data['status']=1;
		
		$data["title"]=$pagename['title'];
		
		$topbanner=$this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']=$pageid;
		}
		
		$scrollingtickerline=$this->adminmodel->getfulldata_scrolling_api("scrollingticker","1");		
		for($i=0; $i < count($scrollingtickerline); $i++){			
			$data["scrollingticker"][$i]['content']=$scrollingtickerline[$i]['scrollingticker'];
		}
			
				
		echo json_encode($data);		
		//echo '<pre>'; print_r($data);
	}
	public function homepage_saw()
	{
		die;
		$pageid="59";
		$pagename=$this->adminmodel->getpageid_api("pages",$pageid);
				
		$data=array();		
		$data['status']=1;
		
		$data["title"]=$pagename['title'];
		
		$topbanner=$this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']=$pageid;
		}
		
		$scrollingtickerline=$this->adminmodel->getfulldata_scrolling_api("scrollingticker","1");		
		for($i=0; $i < count($scrollingtickerline); $i++){			
			$data["scrollingticker"][$i]['content']=$scrollingtickerline[$i]['scrollingticker'];
		}
			
				
		echo json_encode($data);		
		//echo '<pre>'; print_r($data);
	}

	
	public function healthandsafety()
	{
		die;
		$pageid="2";
		$pagename=$this->adminmodel->getpageid_api("pages",$pageid);
		
		$data=array();		
		$data['status']=1;
		
		$data["title"]=$pagename['title'];
		
		$data["leftsidebar"][0]['content']="Home";
		$data["leftsidebar"][1]['content']="About";
		$data["leftsidebar"][2]['content']="Help";
		$data["leftsidebar"][3]['content']="T&C's";
		$data["leftsidebar"][4]['content']="Contact us";
		$data["leftsidebar"][5]['content']="Profile";
				
		$topbanner=$this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']=$pageid;
		}
		
		$bottombanner=$this->adminmodel->getfulldata_api("banner",'bottom',$pageid);
		for($i=0; $i < count($bottombanner); $i++){			
			$data['bottombanner'][$i]['image']=base_url().'./images/'.$bottombanner[$i]['image'];
			$data['bottombanner'][$i]['link']=$bottombanner[$i]['link'];
			$data['bottombanner'][$i]['imageid']=$bottombanner[$i]['id'];
			$data['bottombanner'][$i]['pageid']=$pageid;
		}
		
		$backbanner=$this->adminmodel->getfulldata("backbanner");
		for ($i = 0; $i < count($backbanner); $i++) {	
			$data["backgroundbanner"][$i]['image']=base_url().'./images/'.$backbanner[$i]['image'];
		}
		
		$scrollingtickerline=$this->adminmodel->getfulldata_scrolling_api("scrollingticker",$pageid);		
		for($i=0; $i < count($scrollingtickerline); $i++){			
			$data["scrollingticker"][$i]['content']=$scrollingtickerline[$i]['scrollingticker'];
		}
		
		$data["topicon"][0]['image']=base_url().'./appicons/icons15.png';	
		$data["centericon"][0]['title']="Tool Box Talks";
		$data["centericon"][1]['image']=base_url().'./appicons/icons16.png';	
		$data["centericon"][1]['title']="Vehicle Checklist";
		$data["centericon"][2]['image']=base_url().'./appicons/icons17.png';	
		$data["centericon"][2]['title']="Site Risk Assessment";
// 		$data["centericon"][3]['image']=base_url().'./appicons/icons17.png';	
// 		$data["centericon"][3]['title']="About FEM";
		
		echo json_encode($data);
		//echo '<pre>'; print_r($data);
	}
	
	public function healthandsafety_homepage()
	{
		die;
		$pageid="2";
		$pagename=$this->adminmodel->getpageid_api("pages",$pageid);
		$data=array();
		$topbanner=$this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']=$pageid;
		}	
		$data["title"] 						= 	'Health and Safety';
		$data["homeicon"][0]['image']		=	base_url().'/appicons/toolbox.png';	
		$data["homeicon"][0]['title']		=	"Tool box talks";

		$data["homeicon"][1]['image']		=	base_url().'/appicons/vehicle.png';	
		$data["homeicon"][1]['title']		=	"Vehicle checklist";

		$data["homeicon"][2]['image']		=	base_url().'/appicons/siterisk.png';	
		$data["homeicon"][2]['title']		=	"Site risk assessment";		
		echo json_encode($data);	
	}
	
	public function toolboxtalks()
	{
		die;
		$pageid="2";
		$pagename=$this->adminmodel->getpageid_api("pages",$pageid);

		$data=array();
					
		$data['status']=1;
		
		$data["title"]=$pagename['title'];
		
		$data["leftsidebar"][0]['content']="Home";
		$data["leftsidebar"][1]['content']="About";
		$data["leftsidebar"][2]['content']="Help";
		$data["leftsidebar"][3]['content']="T&C's";
		$data["leftsidebar"][4]['content']="Contact us";
		$data["leftsidebar"][5]['content']="Profile";
				
		$topbanner=$this->adminmodel->getfulldata_api("banner",'top',$pageid);

		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']=$pageid;
		}
		
		$bottombanner=$this->adminmodel->getfulldata_api("banner",'bottom',$pageid);
		for($i=0; $i < count($bottombanner); $i++){			
			$data['bottombanner'][$i]['image']=base_url().'./images/'.$bottombanner[$i]['image'];
			$data['bottombanner'][$i]['link']=$bottombanner[$i]['link'];
			$data['bottombanner'][$i]['imageid']=$bottombanner[$i]['id'];
			$data['bottombanner'][$i]['pageid']=$pageid;
		}
		
		$backbanner=$this->adminmodel->getfulldata("backbanner");
		for ($i = 0; $i < count($backbanner); $i++) {	
			$data["backgroundbanner"][$i]['image']=base_url().'./images/'.$backbanner[$i]['image'];
		}
		
		$scrollingtickerline=$this->adminmodel->getfulldata_scrolling_api("scrollingticker",$pageid);		
		for($i=0; $i < count($scrollingtickerline); $i++){			
			$data["scrollingticker"][$i]['content']=$scrollingtickerline[$i]['scrollingticker'];
		}
		
		$toolbox=$this->adminmodel->getfulldata_toolbox_api("toolboxtalks", ['plublish' => '1']);
		for ($i = 0; $i < count($toolbox); $i++) {
			$findtext 		= ['"', "'"];
			$replacetext 	= ['&quot;', '&#039;'];
			$content 		= htmlentities(str_replace($findtext, $replacetext, $toolbox[$i]['content']));


			$data["toolbox"][$i]['id']=$toolbox[$i]['id'];
			$data["toolbox"][$i]['content']=$content;
			$data["toolbox"][$i]['content']=$toolbox[$i]['content'];
			$data["toolbox"][$i]['bgcolorcode']=$toolbox[$i]['bgcolorcode'];
			$data["toolbox"][$i]['type_words'] = $this->config->item('magazinetype')[$toolbox[$i]['type']];
			$data["toolbox"][$i]['type'] = $toolbox[$i]['type'];
			if($toolbox[$i]['description'] !=''){
				$toolbox[$i]['description'] = $toolbox[$i]['description'];
			}else{
				$toolbox[$i]['description'] = '';
			}
			if ($toolbox[$i]['type'] =='1') {
				$data["toolbox"][$i]['image']=base_url().'./images/'.$toolbox[$i]['image'];
			}elseif($toolbox[$i]['type'] =='2'){
				$data["toolbox"][$i]['image']=base_url().'./images/'.$toolbox[$i]['file'];
			}
			if ($toolbox[$i]['type'] =='1') {
				$data["toolbox"][$i]['description'] = $toolbox[$i]['description'];
			}elseif($toolbox[$i]['type'] =='2'){
				$data["toolbox"][$i]['description'] = '';
			}
			if ($toolbox[$i]['feat_file'] !='') {
				$data["toolbox"][$i]['feat_file'] = base_url().'./images/'.$toolbox[$i]['feat_file'];
			}else{
				$data["toolbox"][$i]['feat_file'] = '';
			}
			
			
			$toolbox_subsection=$this->adminmodel->getfulldata_toolboxsubsection_api("toolboxtalkssection1",$toolbox[$i]['id']);
			for ($j = 0; $j < count($toolbox_subsection); $j++) {
				$data["toolbox"][$i]["toolbox_sub"][$j]['id']=$toolbox_subsection[$j]['id'];
				$data["toolbox"][$i]["toolbox_sub"][$j]['content']=$toolbox_subsection[$j]['content'];
				$data["toolbox"][$i]["toolbox_sub"][$j]['description']=$toolbox_subsection[$j]['description'];
				$data["toolbox"][$i]["toolbox_sub"][$j]['detaildescription']=$toolbox_subsection[$j]['detaildescription'];
				$data["toolbox"][$i]["toolbox_sub"][$j]['link']=$toolbox_subsection[$j]['link'];
				// $data["toolbox"][$i]["toolbox_sub"][$j]['image']=base_url().'./images/'.$toolbox_subsection[$j]['image'];
				$data["toolbox"][$i]["toolbox_sub"][$j]['createddate']=$toolbox_subsection[$j]['createddate'];
				$data["toolbox"][$i]["toolbox_sub"][$j]['type_words'] = $this->config->item('magazinetype')[$toolbox_subsection[$j]['type']];
				$data["toolbox"][$i]["toolbox_sub"][$j]['type'] = $toolbox_subsection[$j]['type'];
				if ($toolbox_subsection[$j]['type'] == '2' && $toolbox_subsection[$j]['file'] !='') {
					$data["toolbox"][$i]["toolbox_sub"][$j]['image'] = base_url().'./images/'.$toolbox_subsection[$j]['file'];
				}elseif($toolbox_subsection[$j]['type'] == '1' && $toolbox_subsection[$j]['image'] !=''){
					$data["toolbox"][$i]["toolbox_sub"][$j]['image'] = base_url().'./images/'.$toolbox_subsection[$j]['image'];
				}
				if ($toolbox_subsection[$j]['feat_file'] !='') {
					$data["toolbox"][$i]["toolbox_sub"][$j]['feat_file'] = base_url().'./images/'.$toolbox_subsection[$j]['feat_file'];
				}else{
					$data["toolbox"][$i]["toolbox_sub"][$j]['feat_file'] = '';
				}
			}
		}

		echo json_encode($data);
		// echo '<pre>'; print_r($data);
	}
	
	public function buyandsell()
	{
		die;
		$pageid="5";
		$pagename=$this->adminmodel->getpageid_api("pages",$pageid);
		
		$data=array();		
		$data['status']=1;
		
		$data["title"]=$pagename['title'];
		
		$data["leftsidebar"][0]['content']="Home";
		$data["leftsidebar"][1]['content']="About";
		$data["leftsidebar"][2]['content']="Help";
		$data["leftsidebar"][3]['content']="T&C's";
		$data["leftsidebar"][4]['content']="Contact us";
		$data["leftsidebar"][5]['content']="Profile";
				
		$topbanner=$this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']=$pageid;
		}
		
		$bottombanner=$this->adminmodel->getfulldata_api("banner",'bottom',$pageid);
		for($i=0; $i < count($bottombanner); $i++){			
			$data['bottombanner'][$i]['image']=base_url().'./images/'.$bottombanner[$i]['image'];
			$data['bottombanner'][$i]['link']=$bottombanner[$i]['link'];
			$data['bottombanner'][$i]['imageid']=$bottombanner[$i]['id'];
			$data['bottombanner'][$i]['pageid']=$pageid;
		}
		
		$backbanner=$this->adminmodel->getfulldata("backbanner");
		for ($i = 0; $i < count($backbanner); $i++) {	
			$data["backgroundbanner"][$i]['image']=base_url().'./images/'.$backbanner[$i]['image'];
		}
		
		$scrollingtickerline=$this->adminmodel->getfulldata_scrolling_api("scrollingticker",$pageid);		
		for($i=0; $i < count($scrollingtickerline); $i++){			
			$data["scrollingticker"][$i]['content']=$scrollingtickerline[$i]['scrollingticker'];
		}
				
		echo json_encode($data);
		//echo '<pre>'; print_r($data);
	}
	
	public function category()
	{	
		die;
		$category=$this->adminmodel->getfulldata("category");
		for ($i = 0; $i < count($category); $i++) {	
// 			echo "<option value=".$category[$i]['id'].">".$category[$i]['category']."</option>" ;
	        $data["category"][$i]['id']		=	$category[$i]['id'];
			$data["category"][$i]['name']	=	$category[$i]['category'];

		}
		echo json_encode($data);
	}

	public function buyandcell_homepage()
	{	
		die;
		$pageid="5";
		$pagename=$this->adminmodel->getpageid_api("pages",$pageid);


		$data 								=	array();
		$data["title"] 						=	'Buy and Sell';
		$data["content"] 					=	'Browse Categories';
		$topbanner=$this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']=$pageid;
		}
		$category=$this->adminmodel->getfulldata("category");
		for ($i = 0; $i < count($category); $i++) {	
			$data["category"][$i]['id']		=	$category[$i]['id'];
			$data["category"][$i]['name']	=	$category[$i]['category'];
			$data["category"][$i]['image']	= 	base_url().'./images/'.$category[$i]['image'];
			$data["category"][$i]['image2']	= 	base_url().'./images/'.$category[$i]['image2'];			
		}	
		echo json_encode($data);
	}

	public function buyandcell_whatareyouoffering()
	{	
		die;
		$data 								=	array();
		$data["title"] 						=	'What are you offering?';
		$category=$this->adminmodel->getfulldata("category");
		for ($i = 0; $i < count($category); $i++) {	
			$data["category"][$i]['id']		=	$category[$i]['id'];
			$data["category"][$i]['name']	=	$category[$i]['category'];
			$data["category"][$i]['image']	= 	base_url().'./images/'.$category[$i]['image'];			
		}	
		echo json_encode($data);
	}

	public function buyandcell_allitems()
	{	
		die;
		$data 									=	array();
		$data["title"] 							=	'My Items';
		$query['categoryid'] 					=	$this->input->post("categoryid");
		$query['locationid'] 					=	$this->input->post("locationid");
		$category 								= 	$this->adminmodel->getdata_buyandsell_items($query);
		for ($i = 0; $i < count($category); $i++) {	
			$data["item"][$i]['id']				=	$category[$i]['id'];
			$data["item"][$i]['name']			=	$category[$i]['name'];
			$data["item"][$i]['category']	    =	$category[$i]['categoryid'];
			$data["item"][$i]['price']			=	$category[$i]['price'];
			$data["item"][$i]['brand']			=	$category[$i]['manufacturerbrand'];
			$data["item"][$i]['location']		=	$category[$i]['location'];
			$data["item"][$i]['description']	=	$category[$i]['description'];
			$data["item"][$i]['email']		    =	$category[$i]['email'];
			$data["item"][$i]['cellphone']		=	$category[$i]['cellphone'];
			// $data["item"][$i]['image']			= 	base_url().'./images/'.$category[$i]['image'];
			$imgarray 							= explode(',', $category[$i]['image']);
			foreach ($imgarray as $key => $value) {
				$data["item"][$i]['image'][]	= 	base_url().'./images/'.$value;	
			}			
		}	
		echo json_encode($data);
	}

	public function buyandcell_myitems()
	{	
		die;
		$data 									=	array();
		$query['userid'] 						=	$this->input->post("userid");
		$query['appdata'] 						=	'api';
		$data["title"] 							=	'My Items';
		$category 								= 	$this->adminmodel->getdata_buyandsell_items($query);
		for ($i = 0; $i < count($category); $i++) {	
			$data["item"][$i]['id']				=	$category[$i]['id'];
			$data["item"][$i]['name']			=	$category[$i]['name'];
			$data["item"][$i]['categoryname']	=	$category[$i]['categoryname'];
			$data["item"][$i]['price']			=	$category[$i]['price'];
			$data["item"][$i]['brand']			=	$category[$i]['manufacturerbrand'];
			$data["item"][$i]['location']		=	$category[$i]['location'];
			$imgarray 							= explode(',', $category[$i]['image']);

			foreach ($imgarray as $key => $value) {
				$data["item"][$i]['image'][]	= 	base_url().'./images/'.$value;	
			}
					
		}	
		echo json_encode($data);
	}

	public function buyandcell_categoryitems()
	{	
		die;
		$data 									=	array();
		$query['categoryid'] 					=	$this->input->post("categoryid");
		$category 								= 	$this->adminmodel->getdata_buyandsell_items($query);
		if(count($category) > 0)
		{
			for ($i = 0; $i < count($category); $i++) {	
				$data["item"][$i]['id']					=	$category[$i]['id'];
				$data["item"][$i]['name']				=	$category[$i]['name'];
				$data["item"][$i]['description']		=	$category[$i]['description'];
				$data["item"][$i]['email']				=	$category[$i]['email'];
				$data["item"][$i]['cellphone']			=	$category[$i]['cellphone'];
				$data["item"][$i]['price']				=	$category[$i]['price'];
				$data["item"][$i]['brand']				=	$category[$i]['manufacturerbrand'];
				$data["item"][$i]['location']			=	$category[$i]['location'];
				$data["item"][$i]['image']				= 	base_url().'./images/'.$category[$i]['image'];			
			}	
		}
		echo json_encode($data);
	}
	
	public function location()
	{		
		die;
		//$data=array();		
		//$data['status']=1;		
		//$data["title"]="Add Your Item";		
		$location=$this->adminmodel->getfulldata("location");
		for ($i = 0; $i < count($location); $i++) {
			echo "<option value=".$location[$i]['id'].">".$location[$i]['location']."</option>" ;
			//$data["location"][$i]['locationid']=$location[$i]['id'];
			//$data["location"][$i]['name']=$location[$i]['location'];
		}		
		//echo json_encode($data);
		//echo '<pre>'; print_r($data);
	}
	public function itemlist()
	{		
		die;
		//$data=array();		
		//$data['status']=1;		
		//$data["title"]="Item List";
		
		$condition="";
		$userid=$this->input->post("userid");
		$itemdata=$this->adminmodel->getdata_itemapi($condition,$userid);
		for ($i = 0; $i < count($itemdata); $i++) {	
		
		$itemid=$itemdata[$i]['id'];
		$itemname=$itemdata[$i]['name'];
		$itemimage=$itemdata[$i]['image'];
		if($itemdata[$i]['active'] == 1)
			$itemstatus="Approved";
		else
			$itemstatus="Not Approved";
		
		echo"<li> 
		<img src='".base_url()."images/$itemimage'. style='height:97%;width:auto;padding-top:1px;'> 
		<h2>$itemname</h2>
		<h5>$itemstatus</h5> 
		<p style='float:right;'> 
			<a href='BuyAndSellEditItem.html?iid=$itemid' rel='external'>Edit</a>
			&nbsp;|&nbsp;
			<a href='#' onclick='deleteconf($itemid)' rel='external'>Delete</a>
		</p>
		</li>";
			
			/*
			$data["itemdata"][$i]['id']=$itemdata[$i]['id'];
			$data["itemdata"][$i]['name']=$itemdata[$i]['name'];
						
			$data["itemdata"][$i]['categoryid']=$itemdata[$i]['categoryid'];			
			$categorydata=$this->adminmodel->getsingledata("category",$itemdata[$i]['categoryid']);	
			$categoryname=$categorydata['category'];
			$data["itemdata"][$i]['categoryname']=$categoryname;			
			
			$data["itemdata"][$i]['locationid']=$itemdata[$i]['locationid'];
			$locationdata=$this->adminmodel->getsingledata("location",$itemdata[$i]['locationid']);			
			$locationname=$locationdata['location'];			
			$data["itemdata"][$i]['locationname']=$locationname;
			
			$data["itemdata"][$i]['image']=base_url().'./images/'.$itemdata[$i]['image'];
			$data["itemdata"][$i]['description']=$itemdata[$i]['description'];
			$data["itemdata"][$i]['contactname']=$itemdata[$i]['contactname'];
			$data["itemdata"][$i]['email']=$itemdata[$i]['email'];
			$data["itemdata"][$i]['cellphone']=$itemdata[$i]['cellphone'];
			$data["itemdata"][$i]['price']=$itemdata[$i]['price'];
			$data["itemdata"][$i]['manufacturerbrand']=$itemdata[$i]['manufacturerbrand'];
			$data["itemdata"][$i]['address']=$itemdata[$i]['address'];
			*/
			
		}		
		//echo json_encode($data);
		//echo '<pre>'; print_r($data);
	}
	public function allitemlist()
	{			
		die;
		$condition="1";		
		$itemdata=$this->adminmodel->allitemlist($condition);
		for ($i = 0; $i < count($itemdata); $i++) {	
		
		$itemid=$itemdata[$i]['id'];
		$itemname=$itemdata[$i]['name'];
		$itemimage=$itemdata[$i]['image'];
		$itemprice=$itemdata[$i]['price'];		
		$categorydata=$this->adminmodel->getsingledata("category",$itemdata[$i]['categoryid']);
		$locationdata=$this->adminmodel->getsingledata("location",$itemdata[$i]['locationid']);
		$categoryname = $categorydata['category'];
		$locationname = $locationdata['location'];
		echo"<li> 
		<a href='BuyAndShellItemDetails.html?iid=$itemid' rel='external'>
		<img src='".base_url()."images/$itemimage'. style='height:97%;width:auto;padding-top:1px;'> 
		<h2>$itemname</h2> 
		<p style='float:left; width: 90%;'> 
			<font style='font-weight:bold;color:green;'>$itemprice</font>
			$categoryname
		</p>
		<p style='float:right;'>
			$locationname
		</p>
		</a>
		</li>";
		
		}
	}

	public function additem()
	{		
		die;
		$data=array();				
		
		$data["title"]="Add Your Item";
		
		$this->form_validation->set_rules("cname","Name",'trim|required');
		$this->form_validation->set_rules("categoryid","Category",'trim|required');
		$this->form_validation->set_rules("locationid","Location",'trim|required');
		//$this->form_validation->set_rules("cellphone","Cell Phone",'trim|required|numeric');
		//$this->form_validation->set_rules("email","Email",'trim|required|valid_email');
		if($this->form_validation->run()==FALSE){
			$data['status']=0;			
		}
		else
		{
			$post = $this->input->post();

			if (isset($post['imagefile'])) {
				$data = $this->fileupload(['files' => $post['imagefile'], 'file_name' => $post['imagefile_name']]);
				$file = $data[0];
			}
			
			// define('UPLOAD_DIR', './images/');
			// $imagefile = $this->input->post("imagefile");
			// if($imagefile != ''){
			// 	$image_parts = explode(";base64,", $imagefile);
			// 	//$image_type_aux = explode("image/", $image_parts[0]);
			// 	//$image_type = $image_type_aux[1];
			// 	$image_base64 = base64_decode($image_parts[1]);
			// 	$file = UPLOAD_DIR . uniqid() . '.png';
			// 	file_put_contents($file, $image_base64);
						
				/*$config_image=array();
				$config_image['upload_path']='./images';
				$config_image['allowed_types']='jpg|jpeg|png';
				$config_image['encrypt_name']=TRUE;
				$this->load->library('upload',$config_image);			
				if ( ! $this->upload->do_upload('imageurl'))
				{
					//print_r($this->upload->display_errors()); print_r($this->input->post("imagefile")); die;
				}
				else
				{
					$imagedata=$this->upload->data();
					$imagefile=$imagedata['file_name'];
				} 
				*/
				$insertdata = array(
				"uid" => $this->input->post("userid"),
				"name" => $this->input->post("cname"),			
				"locationid" => $this->input->post("locationid"),
				"categoryid" => $this->input->post("categoryid"),
				"description" => $this->input->post("description"),				
				"contactname" => $this->input->post("contactname"),
				"email" => $this->input->post("email"),
				"cellphone" => $this->input->post("cellphone"),
				"price" => $this->input->post("price"),
				"manufacturerbrand" => $this->input->post("manufacturerbrand"),
				"address" => $this->input->post("address"),
				"active" => $this->input->post("active")
				);
			
				if(isset($file)){
					// $filename=str_replace("./images/","",$file);			
					$insertdata['image']=$file;
				}
				else{
					$insertdata['image']="defaultimage.png";
				}
				
				$statusvalue=$this->adminmodel->insertdata("item",$insertdata);
				$data['status']=$statusvalue;
			}
									
			
		//}
		
		echo json_encode($data);
		//echo '<pre>'; print_r($data);
	}
	
	public function edititem()
	{		
		die;
		$data=array();				
		
		$data["title"]="Edit Your Item";
		
		$itemid = $this->input->post("itemid");
		$itemdata=$this->adminmodel->getsingledata("item",$itemid);
		$i=0;
		$data["itemdata"][$i]['id']=$itemdata['id'];
		$data["itemdata"][$i]['name']=$itemdata['name'];
					
		$data["itemdata"][$i]['categoryid']=$itemdata['categoryid'];			
		$categorydata=$this->adminmodel->getsingledata("category",$itemdata['categoryid']);	
		$categoryname=$categorydata['category'];
		$data["itemdata"][$i]['categoryname']=$categoryname;			
		
		$data["itemdata"][$i]['locationid']=$itemdata['locationid'];
		$locationdata=$this->adminmodel->getsingledata("location",$itemdata['locationid']);			
		$locationname=$locationdata['location'];			
		$data["itemdata"][$i]['locationname']=$locationname;
		
		$data["itemdata"][$i]['image']=base_url().'./images/'.$itemdata['image'];
		$data["itemdata"][$i]['description']=$itemdata['description'];
		$data["itemdata"][$i]['contactname']=$itemdata['contactname'];
		$data["itemdata"][$i]['email']=$itemdata['email'];
		$data["itemdata"][$i]['cellphone']=$itemdata['cellphone'];
		$data["itemdata"][$i]['price']=$itemdata['price'];
		$data["itemdata"][$i]['manufacturerbrand']=$itemdata['manufacturerbrand'];
		$data["itemdata"][$i]['address']=$itemdata['address'];		
		
		echo json_encode($data);
		//echo '<pre>'; print_r($data);
	}
	
	public function edititemsave()
	{		
		die;
		$data=array();				
		
		$data["title"]="Save Your Edit Item";
		
		$itemid = $this->input->post("itemid");
		
		$this->form_validation->set_rules("cname","Name",'trim|required');
		$this->form_validation->set_rules("categoryid","Category",'trim|required');
		$this->form_validation->set_rules("locationid","Location",'trim|required');
		//$this->form_validation->set_rules("cellphone","Cell Phone",'trim|required');
		//$this->form_validation->set_rules("email","Email",'trim|required|valid_email');
		if($this->form_validation->run()==FALSE){
			$data['status']=0;			
		}
		else
		{
			
			define('UPLOAD_DIR', './images/');
			$image_parts = explode(";base64,", $this->input->post("imagefile"));
			//$image_type_aux = explode("image/", $image_parts[0]);
			//$image_type = $image_type_aux[1];
			$image_base64 = base64_decode($image_parts[1]);
			$file = UPLOAD_DIR . uniqid() . '.png';
			file_put_contents($file, $image_base64);			
									
			$updatedata=array(
			"uid" => $this->input->post("userid"),
			"name" => $this->input->post("cname"),			
			"locationid" => $this->input->post("locationid"),
			"categoryid" => $this->input->post("categoryid"),
			"description" => $this->input->post("description"),				
			"contactname" => $this->input->post("contactname"),
			"email" => $this->input->post("email"),
			"cellphone" => $this->input->post("cellphone"),
			"price" => $this->input->post("price"),
			"manufacturerbrand" => $this->input->post("manufacturerbrand"),
			"address" => $this->input->post("address"),
			"active" => $this->input->post("active")
			);
			
			if(isset($file)){
				$filename=str_replace("./images/","",$file);			
				$updatedata['image']=$filename;
			}
			
			$statusvalue=$this->adminmodel->updatedata("item",$updatedata,$itemid);
			$data['status']=$statusvalue;
		}
		
		echo json_encode($data);
		//echo '<pre>'; print_r($data);
	}
	
	public function deleteitem()
	{		
		die;
		$data=array();				
		
		$data["title"]="Delete Item";
		
		$itemid = $this->input->post("itemid");
		$this->adminmodel->deletedata("item",$itemid);
		$data['status']=1;
		echo json_encode($data);
	}
	
	public function singleitem()
	{		
		die;
		$data=array();				
		
		$data["title"]="Edit Your Item";
		
		$itemid = $this->input->post("itemid");
		$itemdata=$this->adminmodel->getsingledata("item",$itemid);
		$i=0;
		$itemname=$itemdata['name'];
		$itemimage=$itemdata['image'];
		$itemprice=$itemdata['price'];		
		$categorydata=$this->adminmodel->getsingledata("category",$itemdata['categoryid']);
		$locationdata=$this->adminmodel->getsingledata("location",$itemdata['locationid']);
		$categoryname = $categorydata['category'];
		$locationname = $locationdata['location'];
		$description=$itemdata['description'];
		$manufacturerbrand=$itemdata['manufacturerbrand'];
		$price=$itemdata['price'];
		
		echo"
		<div class='row'>
		<div class='col-sm-12 col-md-12 col-lg-12'>
		<img src='".base_url()."images/$itemimage' class='img-thumbnail' />
		</div> 
		</div>
		<br/>
		<div class='row'> 
		<div class='col-sm-6 col-md-6 col-lg-6'><b>Category:</b><br />$categoryname</div> 
		<div class='col-sm-6 col-md-6 col-lg-6'><b>Location:</b><br />$locationname</div> 
		</div>
		<br/>
		<div class='row'> 
		<div class='col-sm-12 col-md-6 col-lg-6 padding-top'><b><u>Details</u></b></div> 
		<div class='col-sm-12 col-md-6 col-lg-6'><b>$itemname</b><br />$description</div> 
		</div>
		<br/>
		<div class='row'> 
		<div class='col-sm-6 col-md-6 col-lg-6'><b>Price</b><br /><span style='color:green;'>$price</span></div> 
		<div class='col-sm-6 col-md-6 col-lg-6'><b>Negotiable</b> </br > $manufacturerbrand</div> 
		</div>
		<hr/>
		";
		
	}
	
	public function deleteimage()
	{		
		die;
		$data=array();
		
		$data["title"]="Delete Item Image";
		
		$itemid = $this->input->post("itemid");
		
		$itemdata=$this->adminmodel->getsingledata("item",$itemid);
		$i=0;
		$file='./images/'.$itemdata['image'];
		
		if(file_exists($file)){			
			unlink ($file);
			$data["title"]="Deleted the image";
			$data['status']="1";
		}
		else{
			$data["title"]="Delete image is missing";
			$data['status']="0";
		}		
					
		echo json_encode($data);
	}
		
	public function advertcount()
	{
		die;
		/*
		$imageid = $this->input->post("imageid");
		$gettotalcount = $this->adminmodel->getsingledata("banner",$imageid);
		$totalcount=array("totalcount" => $gettotalcount['totalcount'] + 1);
		$this->adminmodel->updatedata("banner",$totalcount,$imageid);
		$data['banner_totalcount']=$totalcount;		
		$pagesid = $this->input->post("pagesid");
		$gettotalcount = $this->adminmodel->getsingledata("advertising",$pagesid);
		$totalcount=array("totalcount" => $gettotalcount['totalcount'] + 1);
		$this->adminmodel->updatedata("advertising",$totalcount,$pagesid);
		$data['advertising_totalcount']=$totalcount;
		*/
		
		$imageid = $this->input->post("imageid");
		$pagesid = $this->input->post("pagesid");		
		$clickcountdata = $this->adminmodel->advertclickcount_api("advertisingclickcount",$pagesid,$imageid);				
		if($clickcountdata['value'] > 0){
			$updateid=$clickcountdata['data']['id'];
			$totalcount=$clickcountdata['data']['totalcount'] +1;
			$updatedata=array("imageid" => $imageid,"pagesid" => $pagesid,"totalcount" => $totalcount);
			$this->adminmodel->updatedata("advertisingclickcount",$updatedata,$updateid);
			$data['status']=1;
			$data['title']="Count Increased";		
		}
		else{
			$insertdata=array("imageid" => $imageid,"pagesid" => $pagesid,"totalcount" => "1");
			$this->adminmodel->insertdata("advertisingclickcount",$insertdata);
			
			$data['status']=1;
			$data['title']="New Record Added";
		}
		
		echo json_encode($data);			
		
	}
	public function pagescount()
	{		
		die;
		// $pagesid = 2; //$this->input->post("pageid");
		$pagesid = $this->input->post("pageid");
		$curdate = date("Y-m-d h:i:sa");
		$insertdata=array("pagesid" => $pagesid,"date" => $curdate,"count" => "1");
		$this->adminmodel->insertdata("pagescount",$insertdata);
		$data['status']=1;
		$data['title']="New Record Added";
		echo json_encode($data);
	}	
	public function visitingcount()
	{	
	die;	
		$deviceid = $this->input->post("deviceid");
		$userid = $this->input->post("userid");
		$visitingdata = $this->adminmodel->visitingcount_api("appvisitedcount",$deviceid,$userid);
		if($visitingdata['value'] == 0){
			$insertdata=array("deviceid" => $deviceid,"userid" => $userid,"count" => "1");
			$this->adminmodel->insertdata("appvisitedcount",$insertdata);			
			$data['status']=1;
			$data['title']="New Record Added";
			
		}
		$visitingcount = $this->adminmodel->getfulldata("appvisitedcount");
		$data['visitingcount']=count($visitingcount);
		
		echo json_encode($data);
	}
	public function impressionscount()
	{
		die;
		$imageid = $this->input->post("imageid");				
		$clickcountdata = $this->adminmodel->impressionscount_api("banner",$imageid);				
		if($clickcountdata['value'] > 0){
			$updateid=$clickcountdata['data']['id'];
			$totalcount=$clickcountdata['data']['impressions'] + 1;
			$updatedata=array("impressions" => $totalcount);
			$this->adminmodel->updatedata("banner",$updatedata,$updateid);
			$data['status']=1;
			$data['title']="Count Increased";		
		}
		else{			
			$data['status']=0;
			$data['title']="Record not fount";
		}
		
		echo json_encode($data);			
		
	}
	public function sendemail()
	{		
		die;
		
		$config = array();
        $config['useragent']           	= "CodeIgniter";
        $config['mailpath']            	= "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
        $config['protocol']            	= "mail";
        $config['smtp_host']           	= "localhost";
        $config['smtp_port']           	= "25";
        $config['mailtype'] 			= 'html';
        $config['charset']  			= 'utf-8';
        $config['newline']  			= "\r\n";
        $config['wordwrap'] 			= TRUE;

        $this->load->library('email');
        $this->email->initialize($config);
		$this->email->set_newline("\r\n");
		
		$contactname = $this->input->post("contactname");
		$telnumber = $this->input->post("telnumber");
		$message = $this->input->post("message");
		$email = $this->input->post("email");
		
        $this->email->from('info@itfhrm.com', 'Message from App plumber Contactus');
        $this->email->to($email);
        $this->email->subject('Message from App plumber buy/sell');		
		$this->email->message(
		"Dear, ".$contactname."<br/><br/> You have recieved an message from ".$contactname." regarding your item for sale, the user details are: <br/> Email Address: ".$email." <br/> Tel Number: ".$telnumber." <br/> Message :".$message."<br/><br/> Regards <br/> App Plumber");
		$this->email->send();			
					
		echo $this->email->print_debugger();
	}
	
	public function tools()
	{
		die;
		$pageid="6";
		$pagename=$this->adminmodel->getpageid_api("pages",$pageid);
		
		$data=array();		
		$data['status']=1;
		
		$data["title"]=$pagename['title'];
		
		$data["leftsidebar"][0]['content']="Home";
		$data["leftsidebar"][1]['content']="About";
		$data["leftsidebar"][2]['content']="Help";
		$data["leftsidebar"][3]['content']="T&C's";
		$data["leftsidebar"][4]['content']="Contact us";
		$data["leftsidebar"][5]['content']="Profile";
				
		$topbanner=$this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']=$pageid;
		}
		
		$bottombanner=$this->adminmodel->getfulldata_api("banner",'bottom',$pageid);
		for($i=0; $i < count($bottombanner); $i++){			
			$data['bottombanner'][$i]['image']=base_url().'./images/'.$bottombanner[$i]['image'];
			$data['bottombanner'][$i]['link']=$bottombanner[$i]['link'];
			$data['bottombanner'][$i]['imageid']=$bottombanner[$i]['id'];
			$data['bottombanner'][$i]['pageid']=$pageid;
		}
		
		$backbanner=$this->adminmodel->getfulldata("backbanner");
		for ($i = 0; $i < count($backbanner); $i++) {	
			$data["backgroundbanner"][$i]['image']=base_url().'./images/'.$backbanner[$i]['image'];
		}
		
		$scrollingtickerline=$this->adminmodel->getfulldata_scrolling_api("scrollingticker",$pageid);		
		for($i=0; $i < count($scrollingtickerline); $i++){			
			$data["scrollingticker"][$i]['content']=$scrollingtickerline[$i]['scrollingticker'];
		}
		
		$data["centericon"][0]['image']=base_url().'./appicons/hot_water.png';	
		$data["centericon"][0]['title']="Hot water usage calculator";
		$data["centericon"][1]['image']=base_url().'./appicons/calculate_servicerate.png';	
		$data["centericon"][1]['title']="Calculate your service rate";
		$data["centericon"][2]['image']=base_url().'./appicons/calculate_callout.png';	
		$data["centericon"][2]['title']="Calculate a call out rate";
		$data["centericon"][3]['image']=base_url().'./appicons/tourch.png';	
		$data["centericon"][3]['title']="Torch";
		
		echo json_encode($data);
		//echo '<pre>'; print_r($data);
	}


	public function banner_buyandsell_homepage(){
		die;
		$pageid 		= $this->config->item('pagesid')['4'];
		$global 		= $this->config->item('pagesid')['33'];
		$pagename 		= $this->adminmodel->getpageid_api("pages",$pageid);
		$globalpage 	= $this->adminmodel->getpageid_api("pages",$global);

		$data 			= array();		
		$data['status']	= 1;
		
		$data["title"]			= $pagename['title'];
		$data["globaltitle"]	= $globalpage['title'];
		
		$data["leftsidebar"][0]['content']	= "Home";
		$data["leftsidebar"][1]['content']	= "About";
		$data["leftsidebar"][2]['content']	= "Help";
		$data["leftsidebar"][3]['content']	= "T&C's";
		$data["leftsidebar"][4]['content']	= "Contact us";
		$data["leftsidebar"][5]['content']	= "Profile";
				
		$topbanner = $this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']		=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']		=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']	=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']	=$pageid;
		}
		$globaltopbanner = $this->adminmodel->getfulldata_api("banner",'top',$global);
		for($i=0; $i < count($globaltopbanner); $i++){			
			$data['globaltopbanner'][$i]['image']		=base_url().'./images/'.$globaltopbanner[$i]['image'];
			$data['globaltopbanner'][$i]['link']		=$globaltopbanner[$i]['link'];
			$data['globaltopbanner'][$i]['imageid']		=$globaltopbanner[$i]['id'];
			$data['globaltopbanner'][$i]['pageid']		=$global;
		}
		
		// $backbanner=$this->adminmodel->getfulldata("backbanner");
		// for ($i = 0; $i < count($backbanner); $i++) {	
		// 	$data["backgroundbanner"][$i]['image']=base_url().'./images/'.$backbanner[$i]['image'];
		// }
		
		// $scrollingtickerline=$this->adminmodel->getfulldata_scrolling_api("scrollingticker",$pageid);		
		// for($i=0; $i < count($scrollingtickerline); $i++){			
		// 	$data["scrollingticker"][$i]['content']=$scrollingtickerline[$i]['scrollingticker'];
		// }
		
		// $data["centericon"][0]['image']=base_url().'./appicons/hot_water.png';	
		// $data["centericon"][0]['title']="Hot water usage calculator";
		// $data["centericon"][1]['image']=base_url().'./appicons/calculate_servicerate.png';	
		// $data["centericon"][1]['title']="Calculate your service rate";
		// $data["centericon"][2]['image']=base_url().'./appicons/calculate_callout.png';	
		// $data["centericon"][2]['title']="Calculate a call out rate";
		// $data["centericon"][3]['image']=base_url().'./appicons/tourch.png';	
		// $data["centericon"][3]['title']="Torch";
		
		echo json_encode($data);
	}
	public function banner_pirb_homepage(){
		die;
		$pageid 		= $this->config->item('pagesid')['11'];
		$pagename 		= $this->adminmodel->getpageid_api("pages",$pageid);

		$global 		= $this->config->item('pagesid')['33'];
		$globalpage 	= $this->adminmodel->getpageid_api("pages",$global);

		$data 			= array();		
		$data['status']	= 1;
		
		$data["title"]	= $pagename['title'];
		$data["globaltitle"]	= $globalpage['title'];
		
		$data["leftsidebar"][0]['content']	= "Home";
		$data["leftsidebar"][1]['content']	= "About";
		$data["leftsidebar"][2]['content']	= "Help";
		$data["leftsidebar"][3]['content']	= "T&C's";
		$data["leftsidebar"][4]['content']	= "Contact us";
		$data["leftsidebar"][5]['content']	= "Profile";
				
		$topbanner = $this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']		=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']		=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']	=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']	=$pageid;
		}
		$globaltopbanner = $this->adminmodel->getfulldata_api("banner",'top',$global);
		for($i=0; $i < count($globaltopbanner); $i++){			
			$data['globaltopbanner'][$i]['image']		=base_url().'./images/'.$globaltopbanner[$i]['image'];
			$data['globaltopbanner'][$i]['link']		=$globaltopbanner[$i]['link'];
			$data['globaltopbanner'][$i]['imageid']		=$globaltopbanner[$i]['id'];
			$data['globaltopbanner'][$i]['pageid']		=$global;
		}
		
		// $backbanner=$this->adminmodel->getfulldata("backbanner");
		// for ($i = 0; $i < count($backbanner); $i++) {	
		// 	$data["backgroundbanner"][$i]['image']=base_url().'./images/'.$backbanner[$i]['image'];
		// }
		
		// $scrollingtickerline=$this->adminmodel->getfulldata_scrolling_api("scrollingticker",$pageid);		
		// for($i=0; $i < count($scrollingtickerline); $i++){			
		// 	$data["scrollingticker"][$i]['content']=$scrollingtickerline[$i]['scrollingticker'];
		// }
		
		// $data["centericon"][0]['image']=base_url().'./appicons/hot_water.png';	
		// $data["centericon"][0]['title']="Hot water usage calculator";
		// $data["centericon"][1]['image']=base_url().'./appicons/calculate_servicerate.png';	
		// $data["centericon"][1]['title']="Calculate your service rate";
		// $data["centericon"][2]['image']=base_url().'./appicons/calculate_callout.png';	
		// $data["centericon"][2]['title']="Calculate a call out rate";
		// $data["centericon"][3]['image']=base_url().'./appicons/tourch.png';	
		// $data["centericon"][3]['title']="Torch";
		
		echo json_encode($data);
	}
	public function banner_iopsa_homepage(){
		die;
		$pageid 		= $this->config->item('pagesid')['31'];
		$pagename 		= $this->adminmodel->getpageid_api("pages",$pageid);

		$global 		= $this->config->item('pagesid')['33'];
		$globalpage 	= $this->adminmodel->getpageid_api("pages",$global);

		$data 			= array();		
		$data['status']	= 1;
		
		$data["title"]	= $pagename['title'];
		$data["globaltitle"]	= $globalpage['title'];

		$data["leftsidebar"][0]['content']	= "Home";
		$data["leftsidebar"][1]['content']	= "About";
		$data["leftsidebar"][2]['content']	= "Help";
		$data["leftsidebar"][3]['content']	= "T&C's";
		$data["leftsidebar"][4]['content']	= "Contact us";
		$data["leftsidebar"][5]['content']	= "Profile";
				
		$topbanner = $this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']		=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']		=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']	=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']	=$pageid;
		}

		$globaltopbanner = $this->adminmodel->getfulldata_api("banner",'top',$global);
		for($i=0; $i < count($globaltopbanner); $i++){			
			$data['globaltopbanner'][$i]['image']		=base_url().'./images/'.$globaltopbanner[$i]['image'];
			$data['globaltopbanner'][$i]['link']		=$globaltopbanner[$i]['link'];
			$data['globaltopbanner'][$i]['imageid']		=$globaltopbanner[$i]['id'];
			$data['globaltopbanner'][$i]['pageid']		=$global;
		}
		
		// $backbanner=$this->adminmodel->getfulldata("backbanner");
		// for ($i = 0; $i < count($backbanner); $i++) {	
		// 	$data["backgroundbanner"][$i]['image']=base_url().'./images/'.$backbanner[$i]['image'];
		// }
		
		// $scrollingtickerline=$this->adminmodel->getfulldata_scrolling_api("scrollingticker",$pageid);		
		// for($i=0; $i < count($scrollingtickerline); $i++){			
		// 	$data["scrollingticker"][$i]['content']=$scrollingtickerline[$i]['scrollingticker'];
		// }
		
		// $data["centericon"][0]['image']=base_url().'./appicons/hot_water.png';	
		// $data["centericon"][0]['title']="Hot water usage calculator";
		// $data["centericon"][1]['image']=base_url().'./appicons/calculate_servicerate.png';	
		// $data["centericon"][1]['title']="Calculate your service rate";
		// $data["centericon"][2]['image']=base_url().'./appicons/calculate_callout.png';	
		// $data["centericon"][2]['title']="Calculate a call out rate";
		// $data["centericon"][3]['image']=base_url().'./appicons/tourch.png';	
		// $data["centericon"][3]['title']="Torch";
		
		echo json_encode($data);
	}
	public function banner_contactus(){
		die;
		$pageid 		= $this->config->item('pagesid')['32'];
		$pagename 		= $this->adminmodel->getpageid_api("pages",$pageid);

		$global 		= $this->config->item('pagesid')['33'];
		$globalpage 	= $this->adminmodel->getpageid_api("pages",$global);

		$data 			= array();		
		$data['status']	= 1;
		
		$data["title"]			= $pagename['title'];
		$data["globaltitle"]	= $globalpage['title'];
		
		$data["leftsidebar"][0]['content']	= "Home";
		$data["leftsidebar"][1]['content']	= "About";
		$data["leftsidebar"][2]['content']	= "Help";
		$data["leftsidebar"][3]['content']	= "T&C's";
		$data["leftsidebar"][4]['content']	= "Contact us";
		$data["leftsidebar"][5]['content']	= "Profile";
				
		$topbanner = $this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']		=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']		=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']	=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']	=$pageid;
		}

		$globaltopbanner = $this->adminmodel->getfulldata_api("banner",'top',$global);
		for($i=0; $i < count($globaltopbanner); $i++){			
			$data['globaltopbanner'][$i]['image']		=base_url().'./images/'.$globaltopbanner[$i]['image'];
			$data['globaltopbanner'][$i]['link']		=$globaltopbanner[$i]['link'];
			$data['globaltopbanner'][$i]['imageid']		=$globaltopbanner[$i]['id'];
			$data['globaltopbanner'][$i]['pageid']		=$global;
		}
		
		// $backbanner=$this->adminmodel->getfulldata("backbanner");
		// for ($i = 0; $i < count($backbanner); $i++) {	
		// 	$data["backgroundbanner"][$i]['image']=base_url().'./images/'.$backbanner[$i]['image'];
		// }
		
		// $scrollingtickerline=$this->adminmodel->getfulldata_scrolling_api("scrollingticker",$pageid);		
		// for($i=0; $i < count($scrollingtickerline); $i++){			
		// 	$data["scrollingticker"][$i]['content']=$scrollingtickerline[$i]['scrollingticker'];
		// }
		
		// $data["centericon"][0]['image']=base_url().'./appicons/hot_water.png';	
		// $data["centericon"][0]['title']="Hot water usage calculator";
		// $data["centericon"][1]['image']=base_url().'./appicons/calculate_servicerate.png';	
		// $data["centericon"][1]['title']="Calculate your service rate";
		// $data["centericon"][2]['image']=base_url().'./appicons/calculate_callout.png';	
		// $data["centericon"][2]['title']="Calculate a call out rate";
		// $data["centericon"][3]['image']=base_url().'./appicons/tourch.png';	
		// $data["centericon"][3]['title']="Torch";
		
		echo json_encode($data);
	}
	public function banner_installationguide(){
		die;
		$pageid 		= $this->config->item('pagesid')['30'];
		$pagename 		= $this->adminmodel->getpageid_api("pages",$pageid);

		$global 		= $this->config->item('pagesid')['33'];
		$globalpage 	= $this->adminmodel->getpageid_api("pages",$global);

		$data 			= array();		
		$data['status']	= 1;
		
		$data["title"]			= $pagename['title'];
		$data["globaltitle"]	= $globalpage['title'];
		
		$data["leftsidebar"][0]['content']	= "Home";
		$data["leftsidebar"][1]['content']	= "About";
		$data["leftsidebar"][2]['content']	= "Help";
		$data["leftsidebar"][3]['content']	= "T&C's";
		$data["leftsidebar"][4]['content']	= "Contact us";
		$data["leftsidebar"][5]['content']	= "Profile";
				
		$topbanner = $this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']		=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']		=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']	=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']	=$pageid;
		}

		$globaltopbanner = $this->adminmodel->getfulldata_api("banner",'top',$global);
		for($i=0; $i < count($globaltopbanner); $i++){			
			$data['globaltopbanner'][$i]['image']		=base_url().'./images/'.$globaltopbanner[$i]['image'];
			$data['globaltopbanner'][$i]['link']		=$globaltopbanner[$i]['link'];
			$data['globaltopbanner'][$i]['imageid']		=$globaltopbanner[$i]['id'];
			$data['globaltopbanner'][$i]['pageid']		=$global;
		}
		
		// $backbanner=$this->adminmodel->getfulldata("backbanner");
		// for ($i = 0; $i < count($backbanner); $i++) {	
		// 	$data["backgroundbanner"][$i]['image']=base_url().'./images/'.$backbanner[$i]['image'];
		// }
		
		// $scrollingtickerline=$this->adminmodel->getfulldata_scrolling_api("scrollingticker",$pageid);		
		// for($i=0; $i < count($scrollingtickerline); $i++){			
		// 	$data["scrollingticker"][$i]['content']=$scrollingtickerline[$i]['scrollingticker'];
		// }
		
		// $data["centericon"][0]['image']=base_url().'./appicons/hot_water.png';	
		// $data["centericon"][0]['title']="Hot water usage calculator";
		// $data["centericon"][1]['image']=base_url().'./appicons/calculate_servicerate.png';	
		// $data["centericon"][1]['title']="Calculate your service rate";
		// $data["centericon"][2]['image']=base_url().'./appicons/calculate_callout.png';	
		// $data["centericon"][2]['title']="Calculate a call out rate";
		// $data["centericon"][3]['image']=base_url().'./appicons/tourch.png';	
		// $data["centericon"][3]['title']="Torch";
		
		echo json_encode($data);
	}
	
	public function hotwater()
	{
		die;
		$name = array("Title");
		$description = array(" Electrical Consumption for Hot Water Usage<br /><small>The Solar Calculator is meant
                                        for <font style='color:red;'>GUIDANCE</font> purposes only and that the input
                                        and the calculated results will not necessary lead to practical real-world
                                        outcome and/or results.</small>");
		$nameLength = count($name);

		$HWU = array("
                                    <p><b>Hot Water Usage</b></p>
                                    <p id='HWU'>A 2 member household on average has a daily hot water usage of 100
                                        litres. Using an average daily hot water usage of 100 litres, the electrical
                                        energy used to heat this volume of water would be an estimated 174 KwH per
                                        month. At the current cost of R3 per KwH used the estimate electrical bill for a
                                        30 day month would amount to R523 per month.</p>
                                    <p><b>Electrical Savings</b></p>
                                    <p id='ES'>With a savings (Life Style Consideration) of 40% as indicated it is
                                        estimated that one would save R209 per month for the first year by installing a
                                        hot water renewable product. This would amount to R2508 for the first year or
                                        R18732 over a 5 year period.</p>");



		for($x = 0; $x < $nameLength; $x++) {
			$response[] = array("name" => $name[$x], "description" => $description[$x],"description2"=>$HWU); 
		}
		$pageid="6";
		$pagename=$this->adminmodel->getpageid_api("pages",$pageid);
		$data=array();	
		$topbanner=$this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']=$pageid;
		}

		$json = array("status" => 1, "data" => $response,"topbanner" => $data);
		header('Content-type: application/json');
		echo json_encode($json);
	}
	
	public function addserviceratedefault()
	{
		die;
		$pageid="26";
		$pagename=$this->adminmodel->getpageid_api("pages",$pageid);
		
		$data=array();		
		$data['status']=1;
		
		$data["title"]=$pagename['title'];
		
		$uid = $this->input->post("uid");
		$cname = $this->input->post("cname");
		$serviceratesdata = $this->adminmodel->getservicerates_api("servicerates",$uid,$cname);
		if (count($serviceratesdata) > 0){
			for($i=0; $i < count($serviceratesdata); $i++){
				$data['serviceid'] = $serviceratesdata[$i]['serviceid'];
			}
		}
		else{
			$ServiceRatesDefault = $this->adminmodel->getServiceRatesDefault_api("serviceratesdefault");			
			$insertID="";
			for($i=0; $i < count($ServiceRatesDefault); $i++){
				
				$servicecategoriesname = $ServiceRatesDefault[$i]['category'];
				$servicecategoriesiddata = $this->adminmodel->getservicecategoriesid("servicecategories",$servicecategoriesname);
				$servicecategoriesid = $servicecategoriesiddata['categoryid'];
				
				$servicesubcategoriesname = $ServiceRatesDefault[$i]['subcategory'];
				$servicesubcategoriesiddata = $this->adminmodel->getsubservicecategoriesid("servicesubcategories",$servicesubcategoriesname);
				$servicesubcategoriesid = $servicesubcategoriesiddata['subcategoryid'];
				
				$InsertData = array(
				'uid' => $uid,
				'cname' => $cname,
				'category' => $servicecategoriesname,
				'categoryid' => $servicecategoriesid,
				'subcategory' => $servicesubcategoriesname,	
				'subcategoryid' => $servicesubcategoriesid,	
				'monthlycost' => $ServiceRatesDefault[$i]['monthlycost'],
				'createdate' => date('Y-m-d H:i:s')
				);
				$insertID .= $this->adminmodel->insertdata("servicerates",$InsertData).",";		
			}
			$data['serviceid']=$insertID;
		}
		echo json_encode($data);
		//echo '<pre>'; print_r($data);
	}
	public function servicecategorydropdown()
	{		
		die;
		$servicecategory=$this->adminmodel->getfulldata("servicecategories");
		for ($i = 0; $i < count($servicecategory); $i++) {
			echo "<option value=".$servicecategory[$i]['categoryid'].">".$servicecategory[$i]['category']."</option>" ;
		}
	}
	
	public function servicesubcategorydropdown()
	{	
	die;	
		$categoryid = $this->input->post("categoryid");
		$servicesubcategory=$this->adminmodel->getservicesubcatdropdown("servicesubcategories",$categoryid);
		
		for ($i = 0; $i < count($servicesubcategory); $i++) {
			echo "<option value=".$servicesubcategory[$i]['subcategoryid'].">".$servicesubcategory[$i]['subcategory']."</option>" ;
		}
	}
	
	public function getservicerates()
	{		
		die;
		$uid = $this->input->post("uid");  
		$cname = $this->input->post("cname");

		$servicecategory = $this->adminmodel->getservicecategoryfulldata("servicecategories");
		if(count($servicecategory) > 0){
			for($j=0; $j < count($servicecategory); $j++){
				$categoryid = $servicecategory[$j]['categoryid'];
				$category 	= $servicecategory[$j]['category'];
				echo'<li class="q" id="'.$categoryid.'">'.$category.'</li><div class="a">';
				$label = '';
				if($categoryid == '2'){
					$label = '<label class="notedrp">Remember to include things such as your rent, bond, water and electricity, telephones, equipment, stationary, cartridges, furniture etc. You can add as many categories as you like using the "Add Monthly Cost" button below.</label>';
				}
				elseif($categoryid == '28'){
					$label = '<label class="notedrp">Remember to include things such as your tools, uniforms etc. You can add as many categories as you like using the "Add Monthly Cost" button below.</label>';
				}
				elseif($categoryid == '29'){
					$label = '<label class="notedrp">Remember to include insurances such as key man / life and disability, general office, public liability, product liability, defective workmanship, bakkie, tools and equipment, medical aid, provident fund etc. You can add as many categories as you like using the "Add Monthly Cost" button below.</label>';
				}
				echo $label;

				$getservicerates=$this->adminmodel->getservicerates_api("servicerates",$uid,$cname,$categoryid);
				if(count($getservicerates) > 0){
					for ($i = 0; $i < count($getservicerates); $i++) {
						$editimage=base_url()."images/edit.png";
						$deleteimage=base_url()."images/delete.png";
						$serviceid = $getservicerates[$i]['serviceid'];
						
						if(strlen($getservicerates[$i]['category']) > 0){
						    $addrow = "'".$categoryid."','".$category."','".$serviceid."','".$getservicerates[$i]['subcategory']."','".$serviceid."'";
							echo'
		                            <div class="range---section srvce--rate---range">

		                                <h2 id="'.$serviceid.'">'.$getservicerates[$i]['subcategory'].'</h2>
		                                <div class="Quickbooks">
		                                <div class="rangeee">
		                                    <div class="deletee-btn"><img id="btnAddMonthlyCosts" type="button" value="" onclick="delrow('.$serviceid.')" class="btn-xs" src="./img/delete--btn.png"></div>
		                                    <input type="number" min="1" max="100000" step="1" value="'.$getservicerates[$i]['monthlycost'].'" class="slider" id="myRange'.$serviceid.'">
		                                    <div class="deletee-btn"><img id="btnAddMonthlyCosts" type="button" value="" onclick="addrow('.$addrow.')" class="btn-xs" src="./img/tick-box.png" style="width: 10% !important;float: right !important;margin-right: 0px !important;margin-top: -36px !important;"></div>
		                                </div>
		                                </div>
		                            </div>
		                        ';				
						}									
					}
				}

				else{
				// 	echo '<div class="a">No Monthly Costs</div>' ;
				echo'
		                            <div class="range---section srvce--rate---range">
		                                <h2>No Monthly Costs</h2></div>';
		                                
				}
				echo'</div>';
			}
		}

		
		
	}

	public function getservicerates_old()
	{		
		die;
		$uid = $this->input->post("uid");  // 196372; //
		$cname = $this->input->post("cname");  // "Kanaga"; //
		$getservicerates=$this->adminmodel->getservicerates_api("servicerates",$uid,$cname);
		if(count($getservicerates) > 0){
			for ($i = 0; $i < count($getservicerates); $i++) {
				$editimage=base_url()."images/edit.png";
				$deleteimage=base_url()."images/delete.png";
				$serviceid = $getservicerates[$i]['serviceid'];
				
				if(strlen($getservicerates[$i]['subcategory']) > 0){
echo '<div class="row"><div class="col-sm-6">'.$getservicerates[$i]['category'].' - '.$getservicerates[$i]['subcategory'].'</div><div class="col-sm-6 text-right">'.$getservicerates[$i]['monthlycost'].'&nbsp;<input id="btnAddMonthlyCosts" type="button" value="" onclick="EditCategory('.$serviceid.')" class="btn-xs" style="background-image:url('.$editimage.');background-repeat:no-repeat;width:20px;height:20px;border:none;background-position:center;background-color:#fff;" />&nbsp;<input id="btnAddMonthlyCosts" type="button" value="" onclick="delrow('.$serviceid.')" class="btn-xs" style="background-image:url('.$deleteimage.');background-repeat:no-repeat;width:20px;height:20px;border:none;background-position:center;background-color:#fff;" /></div></div>';									
				}
				else{
echo '<div class="row"><div class="col-sm-6">'.$getservicerates[$i]['category'].'</div><div class="col-sm-6 text-right">'.$getservicerates[$i]['monthlycost'].'&nbsp;<input id="btnAddMonthlyCosts" type="button" value="" onclick="EditCategory('.$serviceid.')" class="btn-xs" style="background-image:url('.$editimage.');background-repeat:no-repeat;width:20px;height:20px;border:none;background-position:center;background-color:#fff;" />&nbsp;<input id="btnAddMonthlyCosts" type="button" value="" onclick="delrow('.$serviceid.')" class="btn-xs" style="background-image:url('.$deleteimage.');background-repeat:no-repeat;width:20px;height:20px;border:none;background-position:center;background-color:#fff;" /></div></div>';
				}				
			}
		}
		else{
			echo '<div class="col-sm-12">No Monthly Costs</div>' ;
		}
	}
	
	public function getserviceratestot()
	{		
		die;
		$uid = $this->input->post("uid");
		$cname = $this->input->post("cname");
		$serviceratesdata = $this->adminmodel->getserviceratestot("servicerates",$uid,$cname);
		if(isset($serviceratesdata['TOT'])){			
			echo '<div class="row"><div class="col-sm-6"><b>Total Monthly Costs</b></div><div class="col-sm-6 text-right"><b>R'.$serviceratesdata['TOT'].'</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></div>';
		}
		else{
			echo '<div class="row"><div class="col-sm-6"><b>Total Monthly Costs</b></div><div class="col-sm-6 text-right"><b>R0</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></div>';
		}
	}
	
	public function getserviceratesannualtot()
	{		
		die;
		$uid = $this->input->post("uid");
		$cname = $this->input->post("cname");
		$serviceratesdata = $this->adminmodel->getserviceratestot_year("servicerates",$uid,$cname);
		if(isset($serviceratesdata['TOT'])){			
			echo '<b>R'.$serviceratesdata['TOT'].'</b>';
		}
		else{
			echo '<b>R0</b>';
		}
	}
	
	public function addserviceratecategory()
	{
		die;
		$uid = $this->input->post("uid");
		$cname = $this->input->post("cname");
		$category = $this->input->post("category");
		$categoryid = $this->input->post("categoryid");
		$subcategory = $this->input->post("subcategory");
		$subcategoryid = $this->input->post("subcategoryid");
		$monthlycost = $this->input->post("monthlycost");
		
		if(strlen($uid) > 0 && strlen($cname) > 0 && strlen($category) > 0 && strlen($monthlycost) > 0){
			$insertID="";
			
			$InsertData = array(
			'uid' => $uid,
			'cname' => $cname,
			'category' => $category,
			'categoryid' => $categoryid,
			'subcategory' => $category,
			'subcategoryid' => $subcategoryid,
			'monthlycost' => $monthlycost,
			'createdate' => date('Y-m-d H:i:s')
			);
			$insertID .= $this->adminmodel->insertdata("servicerates",$InsertData).",";		
			
			$data['serviceid']=$insertID;
		}
		echo json_encode($data);
		//echo '<pre>'; print_r($data);
	}
	
	public function editserviceratecategory()
	{
		die;
		
		$serviceid = $this->input->post("serviceid");
		if(strlen($serviceid) > 0){
			$category = $this->input->post("category");
			$categoryid = $this->input->post("categoryid");
			$subcategory = $this->input->post("subcategory");
			$subcategoryid = $this->input->post("subcategoryid");
			$monthlycost = $this->input->post("monthlycost");
				
			$UpdateData = array(
			'category' => $category,
			'categoryid' => $categoryid,
			'subcategory' => $subcategory,
			'subcategoryid' => $subcategoryid,
			'monthlycost' => $monthlycost
			);
			$UpdateDataID = $this->adminmodel->serviceupdatedata("servicerates",$UpdateData,$serviceid);		
			if($UpdateDataID==1){
				$data['serviceid']="Updated";
			}
			else{
				$data['serviceid']="Not Updated";
			}
		}
		else{
			$data['serviceid']="Error";
		}
		
		echo json_encode($data);
		//echo '<pre>'; print_r($data);
	}
	
	public function gdelservicerates()
	{
		die;
		
		$serviceid = $this->input->post("serviceid");
		if(strlen($serviceid) > 0){			
			$this->adminmodel->servicedeletedata("servicerates",$serviceid);					
			$data['serviceid']="Deleted";			
		}
		else{
			$data['serviceid']="Error";
		}
		
		echo json_encode($data);
		//echo '<pre>'; print_r($data);
	}
	
	public function getserviceratesedit()
	{
		die;
		
		$serviceid = $this->input->post("serviceid");
		if(strlen($serviceid) > 0){			
			$serviceratesdata = $this->adminmodel->getserviceratesdata("servicerates",$serviceid);					
			for($i=0; $i < count($serviceratesdata); $i++){
				
				//Get Category dropdown
				$dropdown = "";
				$CatID = "0";
				$servicecategory = $this->adminmodel->getservicecategoryfulldata("servicecategories");
				if(count($servicecategory) > 0){
					for($j=0; $j < count($servicecategory); $j++){
						if($servicecategory[$j]['category'] == $serviceratesdata[$i]['category']){
							$CatID = $servicecategory[$j]['categoryid'];
							$dropdown .= '<option value="'.$servicecategory[$j]['categoryid'].'" selected>'.$servicecategory[$j]['category'].'</option>';								
						}
						else{
							$dropdown .= '<option value="'.$servicecategory[$j]['categoryid'].'">'.$servicecategory[$j]['category'].'</option>';
						}
					}
				}
				else{
					$dropdown = "";
				}
				
				//Get SubCategory dropdown
				$subdropdown = "";
				$SubCatID ="";
				$servicesubcategory = $this->adminmodel->getservicesubcategoryfulldata("servicesubcategories",$CatID);
				if(count($servicesubcategory) > 0){
					for($k=0; $k < count($servicesubcategory); $k++){
						if($servicesubcategory[$k]['subcategory'] == $serviceratesdata[$i]['subcategory']){
							$SubCatID = $servicesubcategory[$k]['subcategoryid'];
							$subdropdown .= '<option value="'.$servicesubcategory[$k]['subcategoryid'].'" selected>'.$servicesubcategory[$k]['subcategory'].'</option>';								
						}
						else{
							$subdropdown .= '<option value="'.$servicesubcategory[$k]['subcategoryid'].'">'.$servicesubcategory[$k]['subcategory'].'</option>';
						}
					}
				}
				else{
					$subdropdown = "";
				}
				
				$CustomCategory = "";
				if($CatID > 0){  
					$CustomCategory = $serviceratesdata[$i]['category'] ;
				}
				
				$subcategorystyle="";
				if(strlen($serviceratesdata[$i]['subcategory']) > 0){
					$subcategorystyle="";
				}
				else{
					$subcategorystyle="display:none;";
				}
				
echo '<div class="col-md-6">Select Category
	</div>		
	<div class="col-md-6">
		<select id="selEditCategory" onchange="populateEditSubDropdown()">
			<option></option>'.$dropdown.'
		</select>
	</div>
	
	<div class="col-md-6" id="editsubcatlbl" style="'.$subcategorystyle.'">Select Sub Category
	</div>		
	<div class="col-md-6" id="editsubcatsel" style="'.$subcategorystyle.'">
		<select id="selEditSubCategory">'.$subdropdown.'</select>
	</div>
		
	<div class="col-md-6">Edit Custom Category
	</div>		
	<div class="col-md-6">
		<input id="EditCustomCategory" type="text" value="'.$CustomCategory.'"/>
	</div>
	
	<div class="col-md-6">Monthly Cost
	</div>		
	<div class="col-md-6">
		<input id="EditMonthlyCosts" type="text" value="'.$serviceratesdata[$i]['monthlycost'].'"/>
	</div>
	
	<div class="col-md-12">
		<input id="EditServiceID" type="hidden" value="'.$serviceratesdata[$i]['serviceid'].'" />
		<input id="btnEditMonthlyCosts" type="button" value="Update" onclick="UpdateCosts()" class="btn-xs"/>
	</div>';
					
			
			}
		}
		else{
			echo "Error";
		}
		
		//echo '<pre>'; print_r($data);
	}
	
	public function geysersavedata()
	{		
		die;
		$userid = $this->input->post("userid");
		$elmid = $this->input->post("elmid");
		$elmhtml = $this->input->post("elmhtml");
		$cname = $this->input->post("cname");
		if(strlen($userid) > 0 && strlen($elmid) > 0 && strlen($elmhtml) > 0 && strlen($cname) > 0){
			$DeletedID = $this->adminmodel->geyserdeletedata("appgeysercalcdata",$userid,$elmid,$elmhtml,$cname);
			$data['message']=" Deleted ".$DeletedID;
			
			$InsertData = array(
			'userid' => $userid,
			'elmid' => $elmid,
			'elmhtml' => $elmhtml,
			'cname' => $cname,
			'createdate' => date('m/d/Y H:i:s')
			);
			$insertID = $this->adminmodel->insertdata("appgeysercalcdata",$InsertData);		
			
			$data['dataid']=$insertID;
			
		}
		else{
			$data['message']="Error";
		}
		
		echo json_encode($data);
		//echo '<pre>'; print_r($data);
	}
	public function geysergetdata()
	{		
		die;
		$userid = $this->input->post("userid");
		$elmid = $this->input->post("elmid");
		$cname = $this->input->post("cname");
		if(strlen($userid) > 0 && strlen($elmid) > 0 && strlen($cname) > 0){
			$geysergetdata = $this->adminmodel->getgeyserdata("appgeysercalcdata",$userid,$elmid,$cname);
			echo $geysergetdata['elmhtml'];			
		}
		else{
			//$data['message']="Error";
		}		
		//echo json_encode($data);
		//echo '<pre>'; print_r($data);
	}
	public function getserviceratespdf()
	{	
		die;
		$getserviceratespdf = $this->adminmodel->getfulldata("serviceratespdf");
		if(count($getserviceratespdf) >0){
		for ($i = 0; $i < count($getserviceratespdf); $i++) {
			echo "<div class='col-sm-12 col-md-4' style='margin-bottom:3px;'><a class='btn btn-info' href='".base_url()."./images/serviceratespdf.jpg'>".$getserviceratespdf[$i]['documentname']."</a>";
		}			
		}
		else{
			$data['message']="Error";
			echo json_encode($data);
		}
		//echo '<pre>'; print_r($data);
	}

	public function commonapi()
	{
		die;
		$pageid = $this->input->post("pageid");	
		$pagename = $this->adminmodel->getpageid_api("pages",$pageid);
				
		$data=array();		
		$data['status']=$pageid;
		
		$data["title"] = $pagename['title'];
		
		$data["leftsidebar"][0]['content']="Home";
		$data["leftsidebar"][1]['content']="About";
		$data["leftsidebar"][2]['content']="Help";
		$data["leftsidebar"][3]['content']="T&C's";
		$data["leftsidebar"][4]['content']="Contact us";
		$data["leftsidebar"][5]['content']="Profile";
				
		$topbanner=$this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']=$pageid;
		}
		
		$bottombanner=$this->adminmodel->getfulldata_api("banner",'bottom',$pageid);
		for($i=0; $i < count($bottombanner); $i++){			
			$data['bottombanner'][$i]['image']=base_url().'./images/'.$bottombanner[$i]['image'];
			$data['bottombanner'][$i]['link']=$bottombanner[$i]['link'];
			$data['bottombanner'][$i]['imageid']=$bottombanner[$i]['id'];
			$data['bottombanner'][$i]['pageid']=$pageid;
		}
		
		$backbanner=$this->adminmodel->getfulldata("backbanner");
		for ($i = 0; $i < count($backbanner); $i++) {	
			$data["backgroundbanner"][$i]['image']=base_url().'./images/'.$backbanner[$i]['image'];
		}
		
		$scrollingtickerline=$this->adminmodel->getfulldata_scrolling_api("scrollingticker",$pageid);		
		for($i=0; $i < count($scrollingtickerline); $i++){			
			$data["scrollingticker"][$i]['content']=$scrollingtickerline[$i]['scrollingticker'];
		}
				
		$plumbingimage=$this->adminmodel->getfulldata("plumbingafrica");	
		for ($i = 0; $i < count($plumbingimage); $i++) {	
			$data["plumbingafrica"][$i]['image']=base_url().'./images/'.$plumbingimage[$i]['image'];
		}		
				
		echo json_encode($data);		
		//echo '<pre>'; print_r($data);
	}
	
	public function contactus()
	{		
		die;
		
		
		$config = array();
        $config['useragent']           = "CodeIgniter";
        $config['mailpath']            = "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
        $config['protocol']            = "mail";
        $config['smtp_host']           = "localhost";
        $config['smtp_port']           = "25";
        $config['mailtype'] = 'html';
        $config['charset']  = 'utf-8';
        $config['newline']  = "\r\n";
        $config['wordwrap'] = TRUE;

        $this->load->library('email');
        $this->email->initialize($config);
		$this->email->set_newline("\r\n");
		
		$contactname = $this->input->post("contactname");
		$email = $this->input->post("email");	
		$message = $this->input->post("message");	
		//$telnumber = $this->input->post("telnumber");				
		//$address = $this->input->post("address");
		
		/*$contactname = "selvamani";
		$telnumber = "9876543210";		
		$email = "selvamani@itflexsolutions.com";	
		$address = "MDU";*/
		
        $this->email->from('info@itfhrm.com', 'Message from App plumber Contactus');
        $this->email->to($email);
        $this->email->subject('Message from App plumber - Contactus'); 
		$this->email->message(
		"Dear, Admin <br/><br/>New Contact details <br/><br/> Name : ".$contactname." <br/> Email : ".$email." <br/> Message: ".$message."<br/><br/> Regards <br/> App Plumber");
        $this->email->send();
		
		$data=array(
		"name" => $this->input->post("contactname"),				
		"email" => $this->input->post("email"),
		"message" => $this->input->post("message")
		);
		$this->adminmodel->insertdata("contact",$data);
					
		echo $this->email->print_debugger();
	}
	
	public function webinar()
	{
		die;
		$pageid = $this->input->post("pageid");	
		$pagename = $this->adminmodel->getpageid_api("pages",$pageid);
				
		$data=array();		
		$data['status']=$pageid;
		
		$data["title"] = $pagename['title'];
		
		$data["leftsidebar"][0]['content']="Home";
		$data["leftsidebar"][1]['content']="About";
		$data["leftsidebar"][2]['content']="Help";
		$data["leftsidebar"][3]['content']="T&C's";
		$data["leftsidebar"][4]['content']="Contact us";
		$data["leftsidebar"][5]['content']="Profile";
				
		$topbanner=$this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']=$pageid;
		}
		
		$bottombanner=$this->adminmodel->getfulldata_api("banner",'bottom',$pageid);
		for($i=0; $i < count($bottombanner); $i++){			
			$data['bottombanner'][$i]['image']=base_url().'./images/'.$bottombanner[$i]['image'];
			$data['bottombanner'][$i]['link']=$bottombanner[$i]['link'];
			$data['bottombanner'][$i]['imageid']=$bottombanner[$i]['id'];
			$data['bottombanner'][$i]['pageid']=$pageid;
		}
		
		$backbanner=$this->adminmodel->getfulldata("backbanner");
		for ($i = 0; $i < count($backbanner); $i++) {	
			$data["backgroundbanner"][$i]['image']=base_url().'./images/'.$backbanner[$i]['image'];
		}
		
		$scrollingtickerline=$this->adminmodel->getfulldata_scrolling_api("scrollingticker",$pageid);		
		for($i=0; $i < count($scrollingtickerline); $i++){			
			$data["scrollingticker"][$i]['content']=$scrollingtickerline[$i]['scrollingticker'];
		}
				
		$plumbingimage=$this->adminmodel->getfulldata("plumbingafrica");	
		for ($i = 0; $i < count($plumbingimage); $i++) {	
			$data["plumbingafrica"][$i]['image']=base_url().'./images/'.$plumbingimage[$i]['image'];
		}
		
		$curdate=date("Y-m-d");
		$webinardata = $this->adminmodel->getwebinardata("webinars",$curdate);				
		for($i=0; $i < count($webinardata); $i++){
			$data["webinarsdata"][$i]['title']=$webinardata[$i]['title'];
			$data["webinarsdata"][$i]['image']=base_url().'./images/'.$webinardata[$i]['image'];
			$data["webinarsdata"][$i]['date']=date("d-m-Y", strtotime($webinardata[$i]['date']));
			$data["webinarsdata"][$i]['time']=$webinardata[$i]['time'];
			$data["webinarsdata"][$i]['duration']=$webinardata[$i]['duration'];
			$data["webinarsdata"][$i]['link']=$webinardata[$i]['link'];
		
		}
				
		echo json_encode($data);		
		//echo '<pre>'; print_r($data);
	}
	
	public function radio()
	{
		die;
		$pageid = $this->input->post("pageid");	
		$pagename = $this->adminmodel->getpageid_api("pages",$pageid);
				
		$data=array();		
		$data['status']=$pageid;
		
		$data["title"] = $pagename['title'];
		
		$data["leftsidebar"][0]['content']="Home";
		$data["leftsidebar"][1]['content']="About";
		$data["leftsidebar"][2]['content']="Help";
		$data["leftsidebar"][3]['content']="T&C's";
		$data["leftsidebar"][4]['content']="Contact us";
		$data["leftsidebar"][5]['content']="Profile";
				
		$topbanner=$this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']=$pageid;
		}
		
		$bottombanner=$this->adminmodel->getfulldata_api("banner",'bottom',$pageid);
		for($i=0; $i < count($bottombanner); $i++){			
			$data['bottombanner'][$i]['image']=base_url().'./images/'.$bottombanner[$i]['image'];
			$data['bottombanner'][$i]['link']=$bottombanner[$i]['link'];
			$data['bottombanner'][$i]['imageid']=$bottombanner[$i]['id'];
			$data['bottombanner'][$i]['pageid']=$pageid;
		}
		
		$backbanner=$this->adminmodel->getfulldata("backbanner");
		for ($i = 0; $i < count($backbanner); $i++) {	
			$data["backgroundbanner"][$i]['image']=base_url().'./images/'.$backbanner[$i]['image'];
		}
		
		$scrollingtickerline=$this->adminmodel->getfulldata_scrolling_api("scrollingticker",$pageid);		
		for($i=0; $i < count($scrollingtickerline); $i++){			
			$data["scrollingticker"][$i]['content']=$scrollingtickerline[$i]['scrollingticker'];
		}
				
		$plumbingimage=$this->adminmodel->getfulldata("plumbingafrica");	
		for ($i = 0; $i < count($plumbingimage); $i++) {	
			$data["plumbingafrica"][$i]['image']=base_url().'./images/'.$plumbingimage[$i]['image'];
		}
		
		$curdate=date("Y-m-d");
		$radiodata = $this->adminmodel->getradiodata("radio",$curdate);				
		for($i=0; $i < count($radiodata); $i++){
			$data["radiodata"][$i]['livestreamlink']=$radiodata[$i]['livestreamlink'];
			$data["radiodata"][$i]['title']=$radiodata[$i]['title'];
			$data["radiodata"][$i]['image']=base_url().'./images/'.$radiodata[$i]['image'];
			$data["radiodata"][$i]['date']=date("d-m-Y", strtotime($radiodata[$i]['date']));
			$data["radiodata"][$i]['time']=$radiodata[$i]['time'];
			$data["radiodata"][$i]['duration']=$radiodata[$i]['duration'];
			$data["radiodata"][$i]['link']=$radiodata[$i]['link'];		
		}
		
		
		$radiodata = $this->adminmodel->getradiodata_singledata("radio",$curdate);				
		for($i=0; $i < count($radiodata); $i++){			
			if($radiodata[$i]['livestreamlink'] == 1){
				$data["radiodata_livestream"][$i]['livestreamlink']=$radiodata[$i]['livestreamlink'];
				$data["radiodata_livestream"][$i]['title']=$radiodata[$i]['title'];
				$data["radiodata_livestream"][$i]['image']=base_url().'./images/'.$radiodata[$i]['image'];
				$data["radiodata_livestream"][$i]['date']=date("d-m-Y", strtotime($radiodata[$i]['date']));
				$data["radiodata_livestream"][$i]['time']=$radiodata[$i]['time'];
				$data["radiodata_livestream"][$i]['duration']=$radiodata[$i]['duration'];
				$data["radiodata_livestream"][$i]['link']=$radiodata[$i]['link'];				
			}		
		}
				
		echo json_encode($data);		
		//echo '<pre>'; print_r($data);
	}



	public function logo()
	{
		die;
		$data=array();	
		$data["title"] 						= 	'Logo';
		$data["iopsa_logo"] 				= 	base_url().'./images/iopsalogo.png';
		$data["pirb_logo"] 					= 	base_url().'./images/pribLogo.png';
		echo json_encode($data);
	}

	public function iopsa_membershipfees()
	{
		die;
		$pageid 		= $this->config->item('pagesid')['31'];
		$pagename 		= $this->adminmodel->getpageid_api("pages",$pageid);

		$global 		= $this->config->item('pagesid')['33'];
		$globalpage 	= $this->adminmodel->getpageid_api("pages",$global);

		$data["globaltitle"]	= $globalpage['title'];

		$globaltopbanner = $this->adminmodel->getfulldata_api("banner",'top',$global);
		for($i=0; $i < count($globaltopbanner); $i++){			
			$data['globaltopbanner'][$i]['image']		=base_url().'./images/'.$globaltopbanner[$i]['image'];
			$data['globaltopbanner'][$i]['link']		=$globaltopbanner[$i]['link'];
			$data['globaltopbanner'][$i]['imageid']		=$globaltopbanner[$i]['id'];
			$data['globaltopbanner'][$i]['pageid']		=$global;
		}

				
		$data=array();	
		$topbanner = $this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']		=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']		=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']	=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']	=$pageid;
		}
		$data["title"] 						= 	'Members Benefits';		
		$data["application_title"] 			= 	'IOPSA Application';	
		$data["membershipfees_title"] 		= 	'IOPSA Membership Fees';		
		$getfulldata 						=	$this->adminmodel->getfulldata("iopsa_membershipfees");	
		$data["associate_annual"] 			= 	$getfulldata[0]['associate_annual'];
		$data["associate_monthly"] 			= 	$getfulldata[0]['associate_monthly'];
		$data["plumbing_reg_annual"] 		= 	$getfulldata[0]['plumbing_reg_annual'];
		$data["plumbing_reg_monthly"] 		= 	$getfulldata[0]['plumbing_reg_monthly'];
		$data["plumbing_nat_annual"] 		= 	$getfulldata[0]['plumbing_nat_annual'];
		$data["merchant_reg_annual"] 		= 	$getfulldata[0]['merchant_reg_annual'];
		$data["merchant_reg_monthly"] 		= 	$getfulldata[0]['merchant_reg_monthly'];
		$data["merchant_nat_annual"] 		= 	$getfulldata[0]['merchant_nat_annual'];
		$data["manufacturer_reg_annual"] 	= 	$getfulldata[0]['manufacturer_reg_annual'];
		$data["manufacturer_reg_monthly"] 	= 	$getfulldata[0]['manufacturer_reg_monthly'];
		$data["manufacturer_nat_annual"] 	= 	$getfulldata[0]['manufacturer_nat_annual'];
		$data["image"] 						= 	base_url().'./images/'.$getfulldata[0]['image'];
		$data["downloadlink"] 				= 	$getfulldata[0]['downloadlink'];
				
		echo json_encode($data);	
	}	

	public function iopsa_homepage()
	{
		die;
		$pageid 		= $this->config->item('pagesid')['31'];
		$pagename 		= $this->adminmodel->getpageid_api("pages",$pageid);

		$global 		= $this->config->item('pagesid')['33'];
		$globalpage 	= $this->adminmodel->getpageid_api("pages",$global);

		$data["globaltitle"]	= $globalpage['title'];

		$globaltopbanner = $this->adminmodel->getfulldata_api("banner",'top',$global);
		for($i=0; $i < count($globaltopbanner); $i++){			
			$data['globaltopbanner'][$i]['image']		=base_url().'./images/'.$globaltopbanner[$i]['image'];
			$data['globaltopbanner'][$i]['link']		=$globaltopbanner[$i]['link'];
			$data['globaltopbanner'][$i]['imageid']		=$globaltopbanner[$i]['id'];
			$data['globaltopbanner'][$i]['pageid']		=$global;
		}
	
		$data=array();
		$topbanner = $this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']		=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']		=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']	=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']	=$pageid;
		}	
		$data["title"] 						= 	'IOPSA Home Page';				
		$getfulldata 						=	$this->adminmodel->getfulldata("iopsa_homepage");	
		$data["content"] 					= 	$getfulldata[0]['content'];	
		$data["homeicon"][0]['image']		=	base_url().'/appicons/iopsa_plumber_new.png';	
		$data["homeicon"][0]['title']		=	"IOPSA Plumbers";
		$data["homeicon"][1]['image']		=	base_url().'/appicons/contctuspng.png';	
		$data["homeicon"][1]['title']		=	"Contact Us";
		$data["homeicon"][2]['image']		=	base_url().'/appicons/memberpng.png';	
		$data["homeicon"][2]['title']		=	"Become a Member";
		$data["homeicon"][3]['image']		=	base_url().'/appicons/aimpng.png';	
		$data["homeicon"][3]['title']		=	"Aims & Objectives";
		$data["homeicon"][4]['image']		=	base_url().'/appicons/shoppng.png';	
		$data["homeicon"][4]['title']		=	"IOPSA Shop";
		$data["homeicon"][5]['image']		=	base_url().'/appicons/lmspng.png';	
		$data["homeicon"][5]['title']		=	"LMS";			
		echo json_encode($data);	
	}

	public function iopsa_contactus()
	{
		die;
		
		$data=array();	
		if($this->input->post("name") != '' && $this->input->post("email") != '' && $this->input->post("message") != '')
		{		

			//Email send
			$getfulldata2 						=	$this->adminmodel->getfulldata("iopsa_settings");	
			$contactus_email 					= 	$getfulldata2[0]['email'];
			// $this->load->library('parser');

			// $config = array();
	        // $config['useragent']         = "CodeIgniter";
	        $config['mailpath']            	= "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
	        $config['mailpath'] 			= '/usr/sbin/sendmail';
	        // $config['protocol']            	= "mail";
	        $config['protocol']            	= "sendmail";
	        // $config['smtp_host']           	= "localhost";
	        // $config['smtp_port']           	= "25";
	        $config['mailtype'] 			= 'html';
	        // $config['charset']  			= 'utf-8';
	        $config['charset']  			= 'iso-8859-1';
	        // $config['newline']  			= "\r\n";
	        $config['wordwrap'] 			= TRUE;

	        $this->load->library('email');
	        $this->email->initialize($config);
			$this->email->set_newline("\r\n");
			
			$name 		= $this->input->post("name");
			$email 		= $this->input->post("email");
			$message 	= $this->input->post("message");
			
	        // $this->email->from('info@itfhrm.com', 'Message from App IOPSA Contactus');
	        $this->email->from('donotreply@articulateit.co.za', 'Message from APP Plumber IOPSA Contact Us');
	        $this->email->to($contactus_email);
	        $this->email->subject('Message from App IOPSA Contactus');		
			$this->email->message(
			"Good day <br/><br/> You have recieved a message from ".$name." regarding your IOPSA Contactus, the user details are: <br/><br/> Email Address: ".$email." <br/><br/> Message :".$message."<br/><br/>  Note: <br/> This is an auto-generated email.");
			$this->email->send();			
						
			$data['email_status'] = $this->email->print_debugger();

			//Email close

			$insertdata=array(
					"name" => $this->input->post("name"),
					"email" => $this->input->post("email"),
					"message" => $this->input->post("message"),
					"createddate" => date("Y-m-d h:i:s")
					);
			$statusvalue=$this->adminmodel->insertdata("iopsa_contactus",$insertdata);
// 			$data['message']="Inserted successfully";
			$data['message']="Thanks for reaching us. We'll get back to you soon.";

		}
		else{
			$data['message']="Mandatory fields are missing";
		}
		echo json_encode($data);
	}	

	public function iopsa_address()
	{
		die;
		$pageid 		= $this->config->item('pagesid')['31'];
		$pagename 		= $this->adminmodel->getpageid_api("pages",$pageid);

		$global 		= $this->config->item('pagesid')['33'];
		$globalpage 	= $this->adminmodel->getpageid_api("pages",$global);

		$data["globaltitle"]	= $globalpage['title'];

		$globaltopbanner = $this->adminmodel->getfulldata_api("banner",'top',$global);
		for($i=0; $i < count($globaltopbanner); $i++){			
			$data['globaltopbanner'][$i]['image']		=base_url().'./images/'.$globaltopbanner[$i]['image'];
			$data['globaltopbanner'][$i]['link']		=$globaltopbanner[$i]['link'];
			$data['globaltopbanner'][$i]['imageid']		=$globaltopbanner[$i]['id'];
			$data['globaltopbanner'][$i]['pageid']		=$global;
		}

		$data=array();
		$topbanner = $this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']		=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']		=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']	=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']	=$pageid;
		}	
		$data["title"] 						= 	'Contact Us';	
		$data["title_content"] 				= 	'Leave us a message and we`ll get in contact with you as soon as possible.';
		$data["contact_title"] 				= 	'Contact Details';
		$data["address_title"] 				= 	'Physical Address:';			
		$getfulldata 						=	$this->adminmodel->getfulldata("iopsa_address");	
		$data["telephone"] 					= 	$getfulldata[0]['telephone'];
		$data["fax"] 						= 	$getfulldata[0]['fax'];
		$data["email"] 						= 	$getfulldata[0]['email'];
		$data["address"] 					= 	str_replace("\r\n","</br>",$getfulldata[0]['address']);
		echo json_encode($data);
	}	

	public function iopsa_category()
	{
		die;
		$pageid 		= $this->config->item('pagesid')['31'];
		$pagename 		= $this->adminmodel->getpageid_api("pages",$pageid);

		$global 		= $this->config->item('pagesid')['33'];
		$globalpage 	= $this->adminmodel->getpageid_api("pages",$global);

		$data["globaltitle"]	= $globalpage['title'];

		$globaltopbanner = $this->adminmodel->getfulldata_api("banner",'top',$global);
		for($i=0; $i < count($globaltopbanner); $i++){			
			$data['globaltopbanner'][$i]['image']		=base_url().'./images/'.$globaltopbanner[$i]['image'];
			$data['globaltopbanner'][$i]['link']		=$globaltopbanner[$i]['link'];
			$data['globaltopbanner'][$i]['imageid']		=$globaltopbanner[$i]['id'];
			$data['globaltopbanner'][$i]['pageid']		=$global;
		}

		$data=array();	
		$topbanner = $this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']		=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']		=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']	=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']	=$pageid;
		}
		$data["title"] 						= 	'Contact Us - IOPSA Membership';
		$data["title_content"] 				= 	'Leave us a message and we`ll get in contact with you as soon as possible.';				
		$getfulldata 						=	$this->adminmodel->getfulldata("iopsa_category");
		for($i=0; $i < count($getfulldata); $i++){			
			if($getfulldata[$i]['published'] == 1){
				$data["category"][$i]["id"]		=	$getfulldata[$i]['id'];	
				$data["category"][$i]["name"]	=	$getfulldata[$i]['category'];		
			}		
		}
					
		$getfulldata 						=	$this->adminmodel->getfulldata("iopsa_province");
		for($i=0; $i < count($getfulldata); $i++){			
			if($getfulldata[$i]['published'] == 1){
				$data["province"][$i]["id"]		=	$getfulldata[$i]['id'];	
				$data["province"][$i]["name"]	=	$getfulldata[$i]['province'];		
			}		
		}

		echo json_encode($data);
	}

	// public function iopsa_province()
	// {
	// 	$data=array();	
	// 	$data["title"] 						= 	'IOPSA Province';				
	// 	$getfulldata 						=	$this->adminmodel->getfulldata("iopsa_province");
	// 	for($i=0; $i < count($getfulldata); $i++){			
	// 		if($getfulldata[$i]['published'] == 1){
	// 			$data["province"][$i]["id"]		=	$getfulldata[$i]['id'];	
	// 			$data["province"][$i]["name"]	=	$getfulldata[$i]['province'];		
	// 		}		
	// 	}

	// 	echo json_encode($data);
	// }

	public function iopsa_member()
	{
		die;
		if($this->input->post("name") != '' && $this->input->post("email") != '' && $this->input->post("cellphone") != '' && $this->input->post("categoryid") != '' && $this->input->post("provinceid") != '' && $this->input->post("message") != '')
		{
			//Email send
			$getfulldata2 						=	$this->adminmodel->getfulldata("iopsa_settings");	
			$contactus_email 					= 	$getfulldata2[0]['email'];

			$config = array();
	        $config['useragent']           	= "CodeIgniter";
	        $config['mailpath']            	= "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
	        $config['protocol']            	= "mail";
	        $config['smtp_host']           	= "localhost";
	        $config['smtp_port']           	= "25";
	        $config['mailtype'] 			= 'html';
	        $config['charset']  			= 'utf-8';
	        $config['newline']  			= "\r\n";
	        $config['wordwrap'] 			= TRUE;

	        $this->load->library('email');
	        $this->email->initialize($config);
			$this->email->set_newline("\r\n");
			
			$name 		= $this->input->post("name");
			$email 		= $this->input->post("email");
			$message 	= $this->input->post("message");
			
	        $this->email->from('info@itfhrm.com', 'Message from App IOPSA Contactus');
	        $this->email->to($contactus_email);
	        $this->email->subject('Message from App IOPSA Contactus');		
			$this->email->message(
			"Dear, Admin <br/><br/> You have recieved an message from ".$name." regarding your IOPSA Contactus, the user details are: <br/> Email Address: ".$email." <br/> Message :".$message."<br/><br/>  Note: <br/> This is an auto-generated email.");
			$this->email->send();			
						
			$data['email_status'] = $this->email->print_debugger();

			//Email close
			
			$insertdata=array(
					"name" => $this->input->post("name"),
					"email" => $this->input->post("email"),
					"cellphone" => $this->input->post("cellphone"),
					"categoryid" => $this->input->post("categoryid"),
					"provinceid" => $this->input->post("provinceid"),
					"message" => $this->input->post("message"),
					"createddate" => date("Y-m-d h:i:s")
					);
			$statusvalue=$this->adminmodel->insertdata("iopsa_member",$insertdata);
			$data['message']="Inserted successfully";
				$data['message']="Thanks for reaching us. We'll get back to you soon.";
		}
		else{
			$data['message']="Mandatory fields are missing";
		}
		echo json_encode($data);
	}	

	public function iopsa_aimsandobjectives()
	{
		die;
		$pageid 		= $this->config->item('pagesid')['31'];
		$pagename 		= $this->adminmodel->getpageid_api("pages",$pageid);

		$global 		= $this->config->item('pagesid')['33'];
		$globalpage 	= $this->adminmodel->getpageid_api("pages",$global);

		$data["globaltitle"]	= $globalpage['title'];

		$globaltopbanner = $this->adminmodel->getfulldata_api("banner",'top',$global);
		for($i=0; $i < count($globaltopbanner); $i++){			
			$data['globaltopbanner'][$i]['image']		=base_url().'./images/'.$globaltopbanner[$i]['image'];
			$data['globaltopbanner'][$i]['link']		=$globaltopbanner[$i]['link'];
			$data['globaltopbanner'][$i]['imageid']		=$globaltopbanner[$i]['id'];
			$data['globaltopbanner'][$i]['pageid']		=$global;
		}

		$data=array();
		$topbanner = $this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']		=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']		=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']	=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']	=$pageid;
		}	
		$data["title"] 						= 	'Aims and Objectives';	
		$data["vision_title"] 				= 	'Vision';
		$data["mission_title"] 				= 	'Mission';
		$data["objectives_title"] 			= 	'The objectives of IOPSA';			
		$getfulldata 						=	$this->adminmodel->getfulldata("iopsa_aimscontent");	
		$data["content"] 					= 	$getfulldata[0]['content'];
		$data["vision"] 					= 	$getfulldata[0]['vision'];
		$data["mission"] 					= 	$getfulldata[0]['mission'];
		$data["objectives"] 				= 	$getfulldata[0]['objectives'];

		$getfulldata2 						=	$this->adminmodel->getfulldata("iopsa_aimsimage");
		for($i=0; $i < count($getfulldata2); $i++){			
			if($getfulldata2[$i]['published'] == 1){
				$data["image"][$i]			=	base_url().'./images/'.$getfulldata2[$i]['image'];	
			}		
		}

		echo json_encode($data);
	}

	public function pirb_homepage()
	{
		die;
		$pageid 		= $this->config->item('pagesid')['11'];
		$pagename 		= $this->adminmodel->getpageid_api("pages",$pageid);

		$global 		= $this->config->item('pagesid')['33'];
		$globalpage 	= $this->adminmodel->getpageid_api("pages",$global);

		$data["globaltitle"]	= $globalpage['title'];

		$globaltopbanner = $this->adminmodel->getfulldata_api("banner",'top',$global);
		for($i=0; $i < count($globaltopbanner); $i++){			
			$data['globaltopbanner'][$i]['image']		=base_url().'./images/'.$globaltopbanner[$i]['image'];
			$data['globaltopbanner'][$i]['link']		=$globaltopbanner[$i]['link'];
			$data['globaltopbanner'][$i]['imageid']		=$globaltopbanner[$i]['id'];
			$data['globaltopbanner'][$i]['pageid']		=$global;
		}

				
		$data=array();	
		$topbanner = $this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']		=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']		=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']	=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']	=$pageid;
		}
		$data["title"] 						= 	'PIRB Home Page';
		$data["content"] 					= 	'The Plumbing Industry Registration Board works proactively to promote better plumbing practices.';
		$data["homeicon"][0]['image']		=	base_url().'/appicons/pribabout.png';	
		$data["homeicon"][0]['title']		=	"About the PIRB";

		$data["homeicon"][1]['image']		=	base_url().'/appicons/pribreg.png';	
		$data["homeicon"][1]['title']		=	"Register";

		$data["homeicon"][2]['image']		=	base_url().'/appicons/pribcostreg.png';	
		$data["homeicon"][2]['title']		=	"Cost of Registration";

		$data["homeicon"][3]['image']		=	base_url().'/appicons/pribcpdactivities.png';	
		$data["homeicon"][3]['title']		=	"CPD Activities";

		$data["homeicon"][4]['image']		=	base_url().'/appicons/pribrank.png';	
		$data["homeicon"][4]['title']		=	"PIRB Rankings";

		$data["homeicon"][5]['image']		=	base_url().'/appicons/priblicence.png';	
		$data["homeicon"][5]['title']		=	"PIRB Licensing System";

		$data["homeicon"][6]['image']		=	base_url().'/appicons/pribfaq.png';	
		$data["homeicon"][6]['title']		=	"FAQ";

		$data["homeicon"][7]['image']		=	base_url().'/appicons/pribcontact.png';	
		$data["homeicon"][7]['title']		=	"Contact Us";

		$data["homeicon"][8]['image']		=	base_url().'/appicons/pribblog.png';	
		$data["homeicon"][8]['title']		=	"News";			
		echo json_encode($data);	
	}

	public function pirb_register()
	{
		die;
		$pageid 		= $this->config->item('pagesid')['11'];
		$pagename 		= $this->adminmodel->getpageid_api("pages",$pageid);

		$global 		= $this->config->item('pagesid')['33'];
		$globalpage 	= $this->adminmodel->getpageid_api("pages",$global);

		$data["globaltitle"]	= $globalpage['title'];

		$globaltopbanner = $this->adminmodel->getfulldata_api("banner",'top',$global);
		for($i=0; $i < count($globaltopbanner); $i++){			
			$data['globaltopbanner'][$i]['image']		=base_url().'./images/'.$globaltopbanner[$i]['image'];
			$data['globaltopbanner'][$i]['link']		=$globaltopbanner[$i]['link'];
			$data['globaltopbanner'][$i]['imageid']		=$globaltopbanner[$i]['id'];
			$data['globaltopbanner'][$i]['pageid']		=$global;
		}

				
		$data=array();
		$topbanner = $this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']		=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']		=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']	=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']	=$pageid;
		}	
		$data["title"] 						= 	'PIRB Register';

		$getfulldata 						=	$this->adminmodel->getfulldata("pirb_registration");

		$data["why_register_title"] 		= 	'Why register with the PIRB';
		$data["why_register_content"] 		= 	$getfulldata[0]['registration'];

		$data["how_register_title"] 		= 	'How to register with the PIRB';
		$data["register_online_title"] 		= 	'1. Register online';
		$data["register_online_content"] 	= 	'Register Online';
		$data["register_online_link"] 		= 	$getfulldata[0]['registeronline_link'];
		$data["register_manually_title"] 	= 	'2. Register manually';
		$data["register_manually_content"] 	= 	$getfulldata[0]['registermanual'];
		$data["register_manually_content2"] = 	'Download Application';
		$data["register_manually_link"] 	= 	$getfulldata[0]['downloadlink'];

		echo json_encode($data);
	}

	public function pirb_about()
	{
		die;
		$pageid 		= $this->config->item('pagesid')['11'];
		$pagename 		= $this->adminmodel->getpageid_api("pages",$pageid);

		$global 		= $this->config->item('pagesid')['33'];
		$globalpage 	= $this->adminmodel->getpageid_api("pages",$global);

		$data["globaltitle"]	= $globalpage['title'];

		$globaltopbanner = $this->adminmodel->getfulldata_api("banner",'top',$global);
		for($i=0; $i < count($globaltopbanner); $i++){			
			$data['globaltopbanner'][$i]['image']		=base_url().'./images/'.$globaltopbanner[$i]['image'];
			$data['globaltopbanner'][$i]['link']		=$globaltopbanner[$i]['link'];
			$data['globaltopbanner'][$i]['imageid']		=$globaltopbanner[$i]['id'];
			$data['globaltopbanner'][$i]['pageid']		=$global;
		}

		$data=array();
		$topbanner = $this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']		=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']		=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']	=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']	=$pageid;
		}	
		$data["content1_title"] 			= 	'What we do?';
		$data["content2_title"] 			= 	'PIRB is a trusted professional body as recognised by the South African Qualifications Authority (SAQA).';
		$data["content3_title"] 			= 	'Therefore, the PIRB, as a professional body promotes: ';
		$data["footer_title"] 				= 	'SAQA Professional Body Recognition Number: PIRB831';				
		$getfulldata 						=	$this->adminmodel->getfulldata("pirb_aboutcontent");	
		$data["content1"] 					= 	$getfulldata[0]['content1'];
		$data["content2"] 					= 	$getfulldata[0]['content2'];
		$data["content3"] 					= 	$getfulldata[0]['content3'];

		$getfulldata2 						=	$this->adminmodel->getfulldata("pirb_aboutimage");
		for($i=0; $i < count($getfulldata2); $i++){			
			if($getfulldata2[$i]['published'] == 1){
				$data["image"][$i]			=	base_url().'./images/'.$getfulldata2[$i]['image'];	
			}		
		}

		echo json_encode($data);
	}

	public function pirb_cost()
	{
		die;
		$pageid 		= $this->config->item('pagesid')['11'];
		$pagename 		= $this->adminmodel->getpageid_api("pages",$pageid);

		$global 		= $this->config->item('pagesid')['33'];
		$globalpage 	= $this->adminmodel->getpageid_api("pages",$global);

		$data["globaltitle"]	= $globalpage['title'];

		$globaltopbanner = $this->adminmodel->getfulldata_api("banner",'top',$global);
		for($i=0; $i < count($globaltopbanner); $i++){			
			$data['globaltopbanner'][$i]['image']		=base_url().'./images/'.$globaltopbanner[$i]['image'];
			$data['globaltopbanner'][$i]['link']		=$globaltopbanner[$i]['link'];
			$data['globaltopbanner'][$i]['imageid']		=$globaltopbanner[$i]['id'];
			$data['globaltopbanner'][$i]['pageid']		=$global;
		}

		$data=array();	
		$topbanner = $this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']		=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']		=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']	=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']	=$pageid;
		}
		$data["title"] 							= 	'PIRB Cost';	
		$data["currecnytype"] 					= 	'R';			
		// $getfulldata 						=	$this->adminmodel->getfulldata("pirb_cost");
		$this->db->select('pc.*,pcc.*');
		$this->db->from('pirb_cost pc');
		$this->db->join('pirb_costcategory pcc', 'pcc.id = pc.categoryid');
		$this->db->order_by('pc.categoryid ASC');
		$query 									= 	$this->db->get();
		$getfulldata 							=	$query->result_array();

		for($i=0; $i < count($getfulldata); $i++){			
			if($getfulldata[$i]['published'] == 1){
				$data["cost"][$i]['category']	=	$getfulldata[$i]['category'];
				$data["cost"][$i]['name']		=	$getfulldata[$i]['name'];
				$data["cost"][$i]['cost']	 	=	$getfulldata[$i]['cost'];	
			}		
		}

		echo json_encode($data);
	}

	public function pirb_contactus()
	{
		die;
		
		$data=array();	
		if($this->input->post("name") != '' && $this->input->post("email") != '' && $this->input->post("message") != '')
		{		

			//Email send
			$getfulldata2 						=	$this->adminmodel->getfulldata("pirb_settings");	
			$contactus_email 					= 	$getfulldata2[0]['email'];
			// $this->load->library('parser');

			// $config = array();
	        // $config['useragent']           	= "CodeIgniter";
	        // $config['mailpath']            	= "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
	        $config['mailpath'] 				= '/usr/sbin/sendmail';
	        // $config['protocol']            	= "mail";
	        $config['protocol']            		= "sendmail";
	        // $config['smtp_host']           	= "localhost";
	        // $config['smtp_port']           	= "25";
	        $config['mailtype'] 				= 'html';
	        // $config['charset']  				= 'utf-8';
	        $config['charset']  				= 'iso-8859-1';
	        // $config['newline']  				= "\r\n";
	        $config['wordwrap'] 				= TRUE;

	        $this->load->library('email');
	        $this->email->initialize($config);
			$this->email->set_newline("\r\n");
			
			$name 		= $this->input->post("name");
			$email 		= $this->input->post("email");
			$message 	= $this->input->post("message");
			
	        // $this->email->from('info@itfhrm.com', 'Message from App PIRB Contactus');
	        $this->email->from('donotreply@articulateit.co.za', 'Message from APP Plumber PIRB Contact Us');
	        $this->email->to($contactus_email);
	        $this->email->subject('Message from App PIRB Contactus');		
			$this->email->message(
			"Good day <br/><br/> You have recieved a message from ".$name." regarding your PIRB Contactus, the user details are: <br/><br/> Email Address: ".$email." <br/><br/> Message :".$message."<br/><br/> Note: <br/> This is an auto-generated email.");
			$this->email->send();			
						
			$data['email_status'] = $this->email->print_debugger();

			//Email close

			$insertdata=array(
					"name" => $this->input->post("name"),
					"email" => $this->input->post("email"),
					"message" => $this->input->post("message"),
					"createddate" => date("Y-m-d h:i:s")
					);
			$statusvalue=$this->adminmodel->insertdata("pirb_contactus",$insertdata);
// 			$data['message']="Inserted successfully";
			$data['message']="Thanks for reaching us. We'll get back to you soon.";

		}
		else{
			$data['message']="Mandatory fields are missing";
		}
		echo json_encode($data);
	}

	public function pirb_address()
	{
		die;
		$pageid 		= $this->config->item('pagesid')['11'];
		$pagename 		= $this->adminmodel->getpageid_api("pages",$pageid);

		$global 		= $this->config->item('pagesid')['33'];
		$globalpage 	= $this->adminmodel->getpageid_api("pages",$global);

		$data["globaltitle"]	= $globalpage['title'];

		$globaltopbanner = $this->adminmodel->getfulldata_api("banner",'top',$global);
		for($i=0; $i < count($globaltopbanner); $i++){			
			$data['globaltopbanner'][$i]['image']		=base_url().'./images/'.$globaltopbanner[$i]['image'];
			$data['globaltopbanner'][$i]['link']		=$globaltopbanner[$i]['link'];
			$data['globaltopbanner'][$i]['imageid']		=$globaltopbanner[$i]['id'];
			$data['globaltopbanner'][$i]['pageid']		=$global;
		}

		$data=array();
		$topbanner = $this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']		=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']		=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']	=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']	=$pageid;
		}
		$data["title"] 						= 	'Contact Us';	
		$data["title_content"] 				= 	'Leave us a message and we`ll get in contact with you as soon as possible.';
		$data["contact_title"] 				= 	'Contact Details';
		$data["address_title"] 				= 	'Physical Address:';				
		$getfulldata 						=	$this->adminmodel->getfulldata("pirb_address");	
		$data["telephone"] 					= 	$getfulldata[0]['telephone'];
		$data["fax"] 						= 	$getfulldata[0]['fax'];
		$data["email"] 						= 	$getfulldata[0]['email'];
		$data["address"] 					= 	str_replace("\r\n","</br>",$getfulldata[0]['address']);
		echo json_encode($data);
	}

	public function pirb_licensingsystem()
	{
		die;
		$pageid 		= $this->config->item('pagesid')['11'];
		$pagename 		= $this->adminmodel->getpageid_api("pages",$pageid);

		$global 		= $this->config->item('pagesid')['33'];
		$globalpage 	= $this->adminmodel->getpageid_api("pages",$global);

		$data["globaltitle"]	= $globalpage['title'];

		$globaltopbanner = $this->adminmodel->getfulldata_api("banner",'top',$global);
		for($i=0; $i < count($globaltopbanner); $i++){			
			$data['globaltopbanner'][$i]['image']		=base_url().'./images/'.$globaltopbanner[$i]['image'];
			$data['globaltopbanner'][$i]['link']		=$globaltopbanner[$i]['link'];
			$data['globaltopbanner'][$i]['imageid']		=$globaltopbanner[$i]['id'];
			$data['globaltopbanner'][$i]['pageid']		=$global;
		}

		$data=array();
		$topbanner = $this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']		=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']		=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']	=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']	=$pageid;
		}	
		$data["title"] 						= 	'PIRB Licensing System';
		$pirblicensing = $this->adminmodel->getfulldata("pirb_licensing", ['pagename' => 'pirb_licensing']);
		for($i=0; $i < count($pirblicensing); $i++){			
			$data['pirblicensing'][$i]['name']		= $pirblicensing[$i]['title'];
			$data['pirblicensing'][$i]['content']	= $pirblicensing[$i]['content'];
			$data['pirblicensing'][$i]['position']	= $pirblicensing[$i]['position'];
			$data['pirblicensing'][$i]['type']	= $pirblicensing[$i]['type'];
			$data['pirblicensing'][$i]['type_word']	= $this->config->item('magazinetype')[$pirblicensing[$i]['type']];
			if ($pirblicensing[$i]['type'] =='2' && $pirblicensing[$i]['file'] !='') {
				$data['pirblicensing'][$i]['file']	= base_url().'images/'.$pirblicensing[$i]['file'].'';
			}else{
				$data['pirblicensing'][$i]['file']	= '';
			}
		}
		echo json_encode($data);
	}

	public function vehiclechecklist()
	{
		die;
		$pageid="2";
		$pagename=$this->adminmodel->getpageid_api("pages",$pageid);
		
		$data=array();	
		if($this->input->post("user_id") != '')
		{
			$topbanner=$this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']=$pageid;
		}
			$data["title"] 								= 	'Vehicle Checklist';
			$data["history_title"] 						= 	'History';
			$requestdata 								= 	$this->input->post();								
			$getfulldata								= 	$this->adminmodel->getfulldata("vehiclechecklist",$requestdata);

			for($i=0; $i < count($getfulldata); $i++){
				$data["vehicle"][$i]['id']				=	$getfulldata[$i]['id'];
				$data["vehicle"][$i]['createddate']		=	$getfulldata[$i]['createddate'];
				$data["vehicle"][$i]['team']			=	$getfulldata[$i]['team'];
				$data["vehicle"][$i]['registration_no']	=	$getfulldata[$i]['registration_no'];	
			}
		}
		else{
			$data['message']="Mandatory fields are missing";
		}
		echo json_encode($data);
	}

	public function vehiclechecklist_insert()
	{
		die;
		$data=array();
		if($this->input->post("user_id") != '' && $this->input->post("team") != '' && $this->input->post("registration_no") != '')
		{	
			$requestdata	=	$this->input->post();
			$statusvalue	=	$this->adminmodel->action("vehiclechecklist",$requestdata);
			if($statusvalue > 0)
				$data['message']="Inserted successfully";
			else
				$data['message']="Data has some error.";

		}
		else{
			$data['message']="Mandatory fields are missing";
		}
		echo json_encode($data);
	}

	public function vehiclechecklist_delete()
	{
		die;
		$data=array();	
		if($this->input->post("user_id") != '' && $this->input->post("deleteid") != '')
		{
			$data["title"] 								= 	'Vehicle Checklist Delete';
			$requestdata								=	$this->input->post();
			$statusvalue								=	$this->adminmodel->delete("vehiclechecklist",$requestdata);	
			if($statusvalue > 0)
				$data['message']="Deleted successfully";
			else
				$data['message']="Data has some error";
		}
		else{
			$data['message']="Mandatory fields are missing";
		}	
		echo json_encode($data);
	}

	public function vehiclechecklist_view()
	{
		die;
		$pageid="2";
		$pagename=$this->adminmodel->getpageid_api("pages",$pageid);
		
		$data=array();
		if($this->input->post("user_id") != '' && $this->input->post("viewid") != '')
		{
			$topbanner=$this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']=$pageid;
		}
			$data["title"] 						= 	'Vehicle Checklist';
			$requestdata 						= 	$this->input->post();								
			$getfulldata						= 	$this->adminmodel->getfulldata("vehiclechecklist",$requestdata);
			
			if(count($getfulldata)){	
				$data_details 					= 	$this->vehiclechecklist_details($getfulldata);
				$data[] 						= 	$data_details;
			}
			else{
				$data['message']				=	"Record was not found.";
			}			
		}
		else{
			$data['message']					=	"Mandatory fields are missing";
		}
		echo json_encode($data);
	}

	public function vehiclechecklist_details($getfulldata){
		die;
		$data["vehicle"]['createddate']			=	(isset($getfulldata[0]['createddate'])) ? $getfulldata[0]['createddate'] : '';
		$data["vehicle"]['team']				=	(isset($getfulldata[0]['team'])) ? $getfulldata[0]['team'] : '';
		$data["vehicle"]['registration_no']		=	(isset($getfulldata[0]['registration_no'])) ? $getfulldata[0]['registration_no'] : '';
		
		$data["vehicle"]['tyres_title']			=	'Tyres';
		$data["vehicle"]['tyres']				=	$this->config->item('passfail')[(isset($getfulldata[0]['tyres'])) ? $getfulldata[0]['tyres'] : '0'];
		$tyres_image = 'tyres_image';
		$i = 0;
		for($i = 0; $i<10; $i++){
			$index = $i+1;
			$image_index = $tyres_image.$index;
			if (isset($getfulldata[0][$image_index]) && $getfulldata[0][$image_index]!='') {
				$data["vehicle"]['tyres_images'][] = base_url().'images/'.$getfulldata[0][$image_index];
			}
		}
		unset($index);
		unset($image_index);

		

		
		/*if(isset($getfulldata[0]['tyres_image']) && $getfulldata[0]['tyres_image'] != '')
			$data["vehicle"]['tyres_image']			=	(isset($getfulldata[0]['tyres_image'])) ? base_url().'images/'.$getfulldata[0]['tyres_image'] : '';
		if(isset($getfulldata[0]['tyres_image2']) && $getfulldata[0]['tyres_image2'] != '')
			$data["vehicle"]['tyres_image2']			=	(isset($getfulldata[0]['tyres_image2'])) ? base_url().'images/'.$getfulldata[0]['tyres_image2'] : '';

		if(isset($getfulldata[0]['tyres_image3']) && $getfulldata[0]['tyres_image3'] != '')
			$data["vehicle"]['tyres_image3']			=	(isset($getfulldata[0]['tyres_image3'])) ? base_url().'images/'.$getfulldata[0]['tyres_image3'] : '';

		if(isset($getfulldata[0]['tyres_image4']) && $getfulldata[0]['tyres_image4'] != '')
			$data["vehicle"]['tyres_image4']			=	(isset($getfulldata[0]['tyres_image4'])) ? base_url().'images/'.$getfulldata[0]['tyres_image4'] : '';

		if(isset($getfulldata[0]['tyres_image5']) && $getfulldata[0]['tyres_image5'] != '')
			$data["vehicle"]['tyres_image5']			=	(isset($getfulldata[0]['tyres_image5'])) ? base_url().'images/'.$getfulldata[0]['tyres_image5'] : '';

		if(isset($getfulldata[0]['tyres_image6']) && $getfulldata[0]['tyres_image6'] != '')
			$data["vehicle"]['tyres_image6']			=	(isset($getfulldata[0]['tyres_image6'])) ? base_url().'images/'.$getfulldata[0]['tyres_image6'] : '';

		if(isset($getfulldata[0]['tyres_image7']) && $getfulldata[0]['tyres_image7'] != '')
			$data["vehicle"]['tyres_image7']			=	(isset($getfulldata[0]['tyres_image7'])) ? base_url().'images/'.$getfulldata[0]['tyres_image7'] : '';

		if(isset($getfulldata[0]['tyres_image8']) && $getfulldata[0]['tyres_image8'] != '')
			$data["vehicle"]['tyres_image8']			=	(isset($getfulldata[0]['tyres_image8'])) ? base_url().'images/'.$getfulldata[0]['tyres_image8'] : '';

		if(isset($getfulldata[0]['tyres_image9']) && $getfulldata[0]['tyres_image9'] != '')
			$data["vehicle"]['tyres_image9']			=	(isset($getfulldata[0]['tyres_image9'])) ? base_url().'images/'.$getfulldata[0]['tyres_image9'] : '';

		if(isset($getfulldata[0]['tyres_image10']) && $getfulldata[0]['tyres_image10'] != '')
			$data["vehicle"]['tyres_image10']			=	(isset($getfulldata[0]['tyres_image10'])) ? base_url().'images/'.$getfulldata[0]['tyres_image10'] : '';*/
		
		$data["vehicle"]['tyres_faults']		=	(isset($getfulldata[0]['tyres_faults'])) ? $getfulldata[0]['tyres_faults'] : '';

		$data["vehicle"]['lights_title']		=	'Lights (brakes, headlights, indicator)';
		$data["vehicle"]['lights']				=	$this->config->item('passfail')[(isset($getfulldata[0]['lights'])) ? $getfulldata[0]['lights'] : '0'];
		if ($i !=0) {
			$i = 0;
		}
		$lights_image = 'lights_image';
		for($i = 0; $i<10; $i++){
			$index = $i+1;
			$image_index = $lights_image.$index;
			if (isset($getfulldata[0][$image_index]) && $getfulldata[0][$image_index]!='') {
				$data["vehicle"]['lights_images'][] = base_url().'images/'.$getfulldata[0][$image_index];
			}
		}
		unset($index);
		unset($image_index);
		
		/*if(isset($getfulldata[0]['lights_image']) && $getfulldata[0]['lights_image'] != '')
			$data["vehicle"]['lights_image']		=	(isset($getfulldata[0]['lights_image'])) ? base_url().'images/'.$getfulldata[0]['lights_image'] : '';
		if(isset($getfulldata[0]['lights_image2']) && $getfulldata[0]['lights_image2'] != '')
		$data["vehicle"]['lights_image2']		=	(isset($getfulldata[0]['lights_image2'])) ? base_url().'images/'.$getfulldata[0]['lights_image2'] : '';

		if(isset($getfulldata[0]['lights_image3']) && $getfulldata[0]['lights_image3'] != '')
		$data["vehicle"]['lights_image3']		=	(isset($getfulldata[0]['lights_image3'])) ? base_url().'images/'.$getfulldata[0]['lights_image3'] : '';

		if(isset($getfulldata[0]['lights_image4']) && $getfulldata[0]['lights_image4'] != '')
		$data["vehicle"]['lights_image4']		=	(isset($getfulldata[0]['lights_image4'])) ? base_url().'images/'.$getfulldata[0]['lights_image4'] : '';

		if(isset($getfulldata[0]['lights_image5']) && $getfulldata[0]['lights_image5'] != '')
		$data["vehicle"]['lights_image5']		=	(isset($getfulldata[0]['lights_image5'])) ? base_url().'images/'.$getfulldata[0]['lights_image5'] : '';

		if(isset($getfulldata[0]['lights_image6']) && $getfulldata[0]['lights_image6'] != '')
		$data["vehicle"]['lights_image6']		=	(isset($getfulldata[0]['lights_image6'])) ? base_url().'images/'.$getfulldata[0]['lights_image6'] : '';

		if(isset($getfulldata[0]['lights_image7']) && $getfulldata[0]['lights_image7'] != '')
		$data["vehicle"]['lights_image7']		=	(isset($getfulldata[0]['lights_image7'])) ? base_url().'images/'.$getfulldata[0]['lights_image7'] : '';

		if(isset($getfulldata[0]['lights_image8']) && $getfulldata[0]['lights_image8'] != '')
		$data["vehicle"]['lights_image8']		=	(isset($getfulldata[0]['lights_image8'])) ? base_url().'images/'.$getfulldata[0]['lights_image8'] : '';

		if(isset($getfulldata[0]['lights_image9']) && $getfulldata[0]['lights_image9'] != '')
		$data["vehicle"]['lights_image9']		=	(isset($getfulldata[0]['lights_image9'])) ? base_url().'images/'.$getfulldata[0]['lights_image9'] : '';

		if(isset($getfulldata[0]['lights_image10']) && $getfulldata[0]['lights_image10'] != '')
		$data["vehicle"]['lights_image10']		=	(isset($getfulldata[0]['lights_image10'])) ? base_url().'images/'.$getfulldata[0]['lights_image10'] : '';*/
		
		$data["vehicle"]['lights_faults']		=	(isset($getfulldata[0]['lights_faults'])) ? $getfulldata[0]['lights_faults'] : '';

		$data["vehicle"]['windscreen_title']	=	'Windscreen and wipers';
		$data["vehicle"]['windscreen']			=	$this->config->item('passfail')[(isset($getfulldata[0]['windscreen'])) ? $getfulldata[0]['windscreen'] : '0'];
		if ($i !=0) {
			$i = 0;
		}
		$windscreen_image = 'windscreen_image';
		for($i = 0; $i<10; $i++){
			$index = $i+1;
			$image_index = $windscreen_image.$index;
			if (isset($getfulldata[0][$image_index]) && $getfulldata[0][$image_index]!='') {
				$data["vehicle"]['windscreen_images'][] = base_url().'images/'.$getfulldata[0][$image_index];
			}
		}
		unset($index);
		unset($image_index);

		/*if(isset($getfulldata[0]['windscreen_image']) && $getfulldata[0]['windscreen_image'] != '')
			$data["vehicle"]['windscreen_image']	=	(isset($getfulldata[0]['windscreen_image'])) ? base_url().'images/'.$getfulldata[0]['windscreen_image'] : '';
		if(isset($getfulldata[0]['windscreen_image2']) && $getfulldata[0]['windscreen_image2'] != '')
			$data["vehicle"]['windscreen_image2']	=	(isset($getfulldata[0]['windscreen_image2'])) ? base_url().'images/'.$getfulldata[0]['windscreen_image2'] : '';

		if(isset($getfulldata[0]['windscreen_image3']) && $getfulldata[0]['windscreen_image3'] != '')
			$data["vehicle"]['windscreen_image3']	=	(isset($getfulldata[0]['windscreen_image3'])) ? base_url().'images/'.$getfulldata[0]['windscreen_image3'] : '';

		if(isset($getfulldata[0]['windscreen_image4']) && $getfulldata[0]['windscreen_image4'] != '')
			$data["vehicle"]['windscreen_image4']	=	(isset($getfulldata[0]['windscreen_image4'])) ? base_url().'images/'.$getfulldata[0]['windscreen_image4'] : '';

		if(isset($getfulldata[0]['windscreen_image5']) && $getfulldata[0]['windscreen_image5'] != '')
			$data["vehicle"]['windscreen_image5']	=	(isset($getfulldata[0]['windscreen_image5'])) ? base_url().'images/'.$getfulldata[0]['windscreen_image5'] : '';

		if(isset($getfulldata[0]['windscreen_image6']) && $getfulldata[0]['windscreen_image6'] != '')
			$data["vehicle"]['windscreen_image6']	=	(isset($getfulldata[0]['windscreen_image6'])) ? base_url().'images/'.$getfulldata[0]['windscreen_image6'] : '';

		if(isset($getfulldata[0]['windscreen_image7']) && $getfulldata[0]['windscreen_image7'] != '')
			$data["vehicle"]['windscreen_image7']	=	(isset($getfulldata[0]['windscreen_image7'])) ? base_url().'images/'.$getfulldata[0]['windscreen_image7'] : '';

		if(isset($getfulldata[0]['windscreen_image8']) && $getfulldata[0]['windscreen_image8'] != '')
			$data["vehicle"]['windscreen_image8']	=	(isset($getfulldata[0]['windscreen_image8'])) ? base_url().'images/'.$getfulldata[0]['windscreen_image8'] : '';

		if(isset($getfulldata[0]['windscreen_image9']) && $getfulldata[0]['windscreen_image9'] != '')
			$data["vehicle"]['windscreen_image9']	=	(isset($getfulldata[0]['windscreen_image9'])) ? base_url().'images/'.$getfulldata[0]['windscreen_image9'] : '';

		if(isset($getfulldata[0]['windscreen_image10']) && $getfulldata[0]['windscreen_image10'] != '')
			$data["vehicle"]['windscreen_image10']	=	(isset($getfulldata[0]['windscreen_image10'])) ? base_url().'images/'.$getfulldata[0]['windscreen_image10'] : '';*/

		$data["vehicle"]['windscreen_faults']	=	(isset($getfulldata[0]['windscreen_faults'])) ? $getfulldata[0]['windscreen_faults'] : '';

		$data["vehicle"]['body_title']			=	'Body (including branding)';
		$data["vehicle"]['body']				=	$this->config->item('passfail')[(isset($getfulldata[0]['body'])) ? $getfulldata[0]['body'] : '0'];
		if ($i !=0) {
			$i = 0;
		}
		$body_image = 'body_image';
		for($i = 0; $i<10; $i++){
			$index = $i+1;
			$image_index = $body_image.$index;
			if (isset($getfulldata[0][$image_index]) && $getfulldata[0][$image_index]!='') {
				$data["vehicle"]['body_images'][] = base_url().'images/'.$getfulldata[0][$image_index];
			}
		}
		unset($index);
		unset($image_index);
		
		/*if(isset($getfulldata[0]['body_image']) && $getfulldata[0]['body_image'] != '')
			$data["vehicle"]['body_image']			=	(isset($getfulldata[0]['body_image'])) ? base_url().'images/'.$getfulldata[0]['body_image'] : '';
		if(isset($getfulldata[0]['body_image2']) && $getfulldata[0]['body_image2'] != '')
			$data["vehicle"]['body_image2']			=	(isset($getfulldata[0]['body_image2'])) ? base_url().'images/'.$getfulldata[0]['body_image2'] : '';

		if(isset($getfulldata[0]['body_image3']) && $getfulldata[0]['body_image3'] != '')
			$data["vehicle"]['body_image3']			=	(isset($getfulldata[0]['body_image3'])) ? base_url().'images/'.$getfulldata[0]['body_image3'] : '';

		if(isset($getfulldata[0]['body_image4']) && $getfulldata[0]['body_image4'] != '')
			$data["vehicle"]['body_image4']			=	(isset($getfulldata[0]['body_image4'])) ? base_url().'images/'.$getfulldata[0]['body_image4'] : '';

		if(isset($getfulldata[0]['body_image5']) && $getfulldata[0]['body_image5'] != '')
			$data["vehicle"]['body_image5']			=	(isset($getfulldata[0]['body_image5'])) ? base_url().'images/'.$getfulldata[0]['body_image5'] : '';

		if(isset($getfulldata[0]['body_image6']) && $getfulldata[0]['body_image6'] != '')
			$data["vehicle"]['body_image6']			=	(isset($getfulldata[0]['body_image6'])) ? base_url().'images/'.$getfulldata[0]['body_image6'] : '';

		if(isset($getfulldata[0]['body_image7']) && $getfulldata[0]['body_image7'] != '')
			$data["vehicle"]['body_image7']			=	(isset($getfulldata[0]['body_image7'])) ? base_url().'images/'.$getfulldata[0]['body_image7'] : '';

		if(isset($getfulldata[0]['body_image8']) && $getfulldata[0]['body_image8'] != '')
			$data["vehicle"]['body_image8']			=	(isset($getfulldata[0]['body_image8'])) ? base_url().'images/'.$getfulldata[0]['body_image8'] : '';

		if(isset($getfulldata[0]['body_image9']) && $getfulldata[0]['body_image9'] != '')
			$data["vehicle"]['body_image9']			=	(isset($getfulldata[0]['body_image9'])) ? base_url().'images/'.$getfulldata[0]['body_image9'] : '';

		if(isset($getfulldata[0]['body_image10']) && $getfulldata[0]['body_image10'] != '')
			$data["vehicle"]['body_image10']			=	(isset($getfulldata[0]['body_image10'])) ? base_url().'images/'.$getfulldata[0]['body_image10'] : '';*/
		
		$data["vehicle"]['body_faults']			=	(isset($getfulldata[0]['body_faults'])) ? $getfulldata[0]['body_faults'] : '';

		$data["vehicle"]['doorlocks_title']		=	'Door Locks';
		$data["vehicle"]['doorlocks']			=	$this->config->item('passfail')[(isset($getfulldata[0]['doorlocks'])) ? $getfulldata[0]['doorlocks'] : '0'];
		if ($i !=0) {
			$i = 0;
		}
		$doorlocks_image = 'doorlocks_image';
		for($i = 0; $i<10; $i++){
			$index = $i+1;
			$image_index = $doorlocks_image.$index;
			if (isset($getfulldata[0][$image_index]) && $getfulldata[0][$image_index]!='') {
				$data["vehicle"]['doorlocks_images'][] = base_url().'images/'.$getfulldata[0][$image_index];
			}
		}
		unset($index);
		unset($image_index);
		
		/*if(isset($getfulldata[0]['doorlocks_image']) && $getfulldata[0]['doorlocks_image'] != '')
			$data["vehicle"]['doorlocks_image']		=	(isset($getfulldata[0]['doorlocks_image'])) ? base_url().'images/'.$getfulldata[0]['doorlocks_image'] : '';
		if(isset($getfulldata[0]['doorlocks_image2']) && $getfulldata[0]['doorlocks_image2'] != '')
			$data["vehicle"]['doorlocks_image2']		=	(isset($getfulldata[0]['doorlocks_image2'])) ? base_url().'images/'.$getfulldata[0]['doorlocks_image2'] : '';

		if(isset($getfulldata[0]['doorlocks_image3']) && $getfulldata[0]['doorlocks_image3'] != '')
			$data["vehicle"]['doorlocks_image3']		=	(isset($getfulldata[0]['doorlocks_image3'])) ? base_url().'images/'.$getfulldata[0]['doorlocks_image3'] : '';

		if(isset($getfulldata[0]['doorlocks_image4']) && $getfulldata[0]['doorlocks_image4'] != '')
			$data["vehicle"]['doorlocks_image4']		=	(isset($getfulldata[0]['doorlocks_image4'])) ? base_url().'images/'.$getfulldata[0]['doorlocks_image4'] : '';

		if(isset($getfulldata[0]['doorlocks_image5']) && $getfulldata[0]['doorlocks_image5'] != '')
			$data["vehicle"]['doorlocks_image5']		=	(isset($getfulldata[0]['doorlocks_image5'])) ? base_url().'images/'.$getfulldata[0]['doorlocks_image5'] : '';
		if(isset($getfulldata[0]['doorlocks_image6']) && $getfulldata[0]['doorlocks_image6'] != '')
			$data["vehicle"]['doorlocks_image6']		=	(isset($getfulldata[0]['doorlocks_image6'])) ? base_url().'images/'.$getfulldata[0]['doorlocks_image6'] : '';

		if(isset($getfulldata[0]['doorlocks_image7']) && $getfulldata[0]['doorlocks_image7'] != '')
			$data["vehicle"]['doorlocks_image7']		=	(isset($getfulldata[0]['doorlocks_image7'])) ? base_url().'images/'.$getfulldata[0]['doorlocks_image7'] : '';

		if(isset($getfulldata[0]['doorlocks_image8']) && $getfulldata[0]['doorlocks_image8'] != '')
			$data["vehicle"]['doorlocks_image8']		=	(isset($getfulldata[0]['doorlocks_image8'])) ? base_url().'images/'.$getfulldata[0]['doorlocks_image8'] : '';

		if(isset($getfulldata[0]['doorlocks_image9']) && $getfulldata[0]['doorlocks_image9'] != '')
			$data["vehicle"]['doorlocks_image9']		=	(isset($getfulldata[0]['doorlocks_image9'])) ? base_url().'images/'.$getfulldata[0]['doorlocks_image9'] : '';

		if(isset($getfulldata[0]['doorlocks_image10']) && $getfulldata[0]['doorlocks_image10'] != '')
			$data["vehicle"]['doorlocks_image10']		=	(isset($getfulldata[0]['doorlocks_image10'])) ? base_url().'images/'.$getfulldata[0]['doorlocks_image10'] : '';*/

		$data["vehicle"]['doorlocks_faults']	=	(isset($getfulldata[0]['doorlocks_faults'])) ? $getfulldata[0]['doorlocks_faults'] : '';

		$data["vehicle"]['equipment_title']		=	'Equipment and material: secured to roof';
		$data["vehicle"]['equipment']			=	$this->config->item('passfail')[(isset($getfulldata[0]['equipment'])) ? $getfulldata[0]['equipment'] : '0'];
		if ($i !=0) {
			$i = 0;
		}
		$equipment_image = 'equipment_image';
		for($i = 0; $i<10; $i++){
			$index = $i+1;
			$image_index = $equipment_image.$index;
			if (isset($getfulldata[0][$image_index]) && $getfulldata[0][$image_index]!='') {
				$data["vehicle"]['equipment_images'][] = base_url().'images/'.$getfulldata[0][$image_index];
			}
		}
		unset($index);
		unset($image_index);
		
		/*if(isset($getfulldata[0]['equipment_image']) && $getfulldata[0]['equipment_image'] != '')
			$data["vehicle"]['equipment_image']		=	(isset($getfulldata[0]['equipment_image'])) ? base_url().'images/'.$getfulldata[0]['equipment_image'] : '';
		if(isset($getfulldata[0]['equipment_image2']) && $getfulldata[0]['equipment_image2'] != '')
			$data["vehicle"]['equipment_image2']		=	(isset($getfulldata[0]['equipment_image2'])) ? base_url().'images/'.$getfulldata[0]['equipment_image2'] : '';

		if(isset($getfulldata[0]['equipment_image3']) && $getfulldata[0]['equipment_image3'] != '')
			$data["vehicle"]['equipment_image3']		=	(isset($getfulldata[0]['equipment_image3'])) ? base_url().'images/'.$getfulldata[0]['equipment_image3'] : '';

		if(isset($getfulldata[0]['equipment_image4']) && $getfulldata[0]['equipment_image4'] != '')
			$data["vehicle"]['equipment_image4']		=	(isset($getfulldata[0]['equipment_image4'])) ? base_url().'images/'.$getfulldata[0]['equipment_image4'] : '';

		if(isset($getfulldata[0]['equipment_image5']) && $getfulldata[0]['equipment_image5'] != '')
			$data["vehicle"]['equipment_image5']		=	(isset($getfulldata[0]['equipment_image5'])) ? base_url().'images/'.$getfulldata[0]['equipment_image5'] : '';

		if(isset($getfulldata[0]['equipment_image6']) && $getfulldata[0]['equipment_image6'] != '')
			$data["vehicle"]['equipment_image6']		=	(isset($getfulldata[0]['equipment_image6'])) ? base_url().'images/'.$getfulldata[0]['equipment_image6'] : '';

		if(isset($getfulldata[0]['equipment_image7']) && $getfulldata[0]['equipment_image7'] != '')
			$data["vehicle"]['equipment_image7']		=	(isset($getfulldata[0]['equipment_image7'])) ? base_url().'images/'.$getfulldata[0]['equipment_image7'] : '';

		if(isset($getfulldata[0]['equipment_image8']) && $getfulldata[0]['equipment_image8'] != '')
			$data["vehicle"]['equipment_image8']		=	(isset($getfulldata[0]['equipment_image8'])) ? base_url().'images/'.$getfulldata[0]['equipment_image8'] : '';

		if(isset($getfulldata[0]['equipment_image9']) && $getfulldata[0]['equipment_image9'] != '')
			$data["vehicle"]['equipment_image9']		=	(isset($getfulldata[0]['equipment_image9'])) ? base_url().'images/'.$getfulldata[0]['equipment_image9'] : '';

		if(isset($getfulldata[0]['equipment_image10']) && $getfulldata[0]['equipment_image10'] != '')
			$data["vehicle"]['equipment_image10']		=	(isset($getfulldata[0]['equipment_image10'])) ? base_url().'images/'.$getfulldata[0]['equipment_image10'] : '';*/

		$data["vehicle"]['equipment_faults']	=	(isset($getfulldata[0]['equipment_faults'])) ? $getfulldata[0]['equipment_faults'] : '';

		$data["vehicle"]['warningflag_title']	=	'Warning flag available';
		$data["vehicle"]['warningflag']			=	$this->config->item('passfail')[(isset($getfulldata[0]['warningflag'])) ? $getfulldata[0]['warningflag'] : '0'];
		if ($i !=0) {
			$i = 0;
		}
		$warningflag_image = 'warningflag_image';
		for($i = 0; $i<10; $i++){
			$index = $i+1;
			$image_index = $warningflag_image.$index;
			if (isset($getfulldata[0][$image_index]) && $getfulldata[0][$image_index]!='') {
				$data["vehicle"]['warningflag_images'][] = base_url().'images/'.$getfulldata[0][$image_index];
			}
		}
		unset($index);
		unset($image_index);
		
		/*if(isset($getfulldata[0]['warningflag_image']) && $getfulldata[0]['warningflag_image'] != '')
			$data["vehicle"]['warningflag_image']	=	(isset($getfulldata[0]['warningflag_image'])) ? base_url().'images/'.$getfulldata[0]['warningflag_image'] : '';

		if(isset($getfulldata[0]['warningflag_image2']) && $getfulldata[0]['warningflag_image2'] != '')
			$data["vehicle"]['warningflag_image2']	=	(isset($getfulldata[0]['warningflag_image2'])) ? base_url().'images/'.$getfulldata[0]['warningflag_image2'] : '';

		if(isset($getfulldata[0]['warningflag_image3']) && $getfulldata[0]['warningflag_image3'] != '')
			$data["vehicle"]['warningflag_image3']	=	(isset($getfulldata[0]['warningflag_image3'])) ? base_url().'images/'.$getfulldata[0]['warningflag_image3'] : '';

		if(isset($getfulldata[0]['warningflag_image4']) && $getfulldata[0]['warningflag_image4'] != '')
			$data["vehicle"]['warningflag_image4']	=	(isset($getfulldata[0]['warningflag_image4'])) ? base_url().'images/'.$getfulldata[0]['warningflag_image4'] : '';

		if(isset($getfulldata[0]['warningflag_image4']) && $getfulldata[0]['warningflag_image4'] != '')
			$data["vehicle"]['warningflag_image4']	=	(isset($getfulldata[0]['warningflag_image4'])) ? base_url().'images/'.$getfulldata[0]['warningflag_image4'] : '';

		if(isset($getfulldata[0]['warningflag_image5']) && $getfulldata[0]['warningflag_image5'] != '')
			$data["vehicle"]['warningflag_image5']	=	(isset($getfulldata[0]['warningflag_image5'])) ? base_url().'images/'.$getfulldata[0]['warningflag_image5'] : '';

		if(isset($getfulldata[0]['warningflag_image6']) && $getfulldata[0]['warningflag_image6'] != '')
			$data["vehicle"]['warningflag_image6']	=	(isset($getfulldata[0]['warningflag_image6'])) ? base_url().'images/'.$getfulldata[0]['warningflag_image6'] : '';

		if(isset($getfulldata[0]['warningflag_image7']) && $getfulldata[0]['warningflag_image7'] != '')
			$data["vehicle"]['warningflag_image7']	=	(isset($getfulldata[0]['warningflag_image7'])) ? base_url().'images/'.$getfulldata[0]['warningflag_image7'] : '';

		if(isset($getfulldata[0]['warningflag_image8']) && $getfulldata[0]['warningflag_image8'] != '')
			$data["vehicle"]['warningflag_image8']	=	(isset($getfulldata[0]['warningflag_image8'])) ? base_url().'images/'.$getfulldata[0]['warningflag_image8'] : '';

		if(isset($getfulldata[0]['warningflag_image9']) && $getfulldata[0]['warningflag_image9'] != '')
			$data["vehicle"]['warningflag_image9']	=	(isset($getfulldata[0]['warningflag_image9'])) ? base_url().'images/'.$getfulldata[0]['warningflag_image9'] : '';
		
		if(isset($getfulldata[0]['warningflag_image10']) && $getfulldata[0]['warningflag_image10'] != '')
			$data["vehicle"]['warningflag_image10']	=	(isset($getfulldata[0]['warningflag_image10'])) ? base_url().'images/'.$getfulldata[0]['warningflag_image10'] : '';*/
		
		$data["vehicle"]['warningflag_faults']	=	(isset($getfulldata[0]['warningflag_faults'])) ? $getfulldata[0]['warningflag_faults'] : '';

		return $data;
	}

	public function vehiclechecklist_pdf()
	{
		die;
		$pdfurl = $_SERVER['DOCUMENT_ROOT'].'/images/';
		$data=array();
		if($this->input->post("user_id") != '' && $this->input->post("viewid") != '')
		{

			$data["title"] 						= 	'Vehicle Checklist'; 
			$requestdata 						= 	$this->input->post();								
			$getfulldata						= 	$this->adminmodel->getfulldata("vehiclechecklist",$requestdata);
			
			if(count($getfulldata)){
				
				$html 							=
'<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Vehicle Checklist</title>
</head>

<body>	
	<img src="'.$pdfurl.'iopsalogo.png">
	<h1>Vehicle Checklist</h1>
	<p>'.$getfulldata[0]['createddate'].'</p>
	<p>'.$getfulldata[0]['team'].'</p>
	<p>'.$getfulldata[0]['registration_no'].'</p>

	<p><h2>Tyres<h2></p>
	<p>'.$this->config->item('passfail')[$getfulldata[0]['tyres']].'</p>';

	if(isset($getfulldata[0]['tyres_image']) && $getfulldata[0]['tyres_image'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['tyres_image'].'"> </p>';
	}
	if(isset($getfulldata[0]['tyres_image2']) && $getfulldata[0]['tyres_image2'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['tyres_image2'].'"> </p>';
	}
	if(isset($getfulldata[0]['tyres_image3']) && $getfulldata[0]['tyres_image3'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['tyres_image3'].'"> </p>';
	}
	if(isset($getfulldata[0]['tyres_image4']) && $getfulldata[0]['tyres_image4'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['tyres_image4'].'"> </p>';
	}
	if(isset($getfulldata[0]['tyres_image5']) && $getfulldata[0]['tyres_image5'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['tyres_image5'].'"> </p>';
	}
	if(isset($getfulldata[0]['tyres_image6']) && $getfulldata[0]['tyres_image6'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['tyres_image6'].'"> </p>';
	}
	if(isset($getfulldata[0]['tyres_image7']) && $getfulldata[0]['tyres_image7'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['tyres_image7'].'"> </p>';
	}
	if(isset($getfulldata[0]['tyres_image8']) && $getfulldata[0]['tyres_image8'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['tyres_image8'].'"> </p>';
	}
	if(isset($getfulldata[0]['tyres_image9']) && $getfulldata[0]['tyres_image9'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['tyres_image9'].'"> </p>';
	}
	if(isset($getfulldata[0]['tyres_image10']) && $getfulldata[0]['tyres_image10'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['tyres_image10'].'"> </p>';
	}
		
	$html 		=	$html.'<p>'.$getfulldata[0]['tyres_faults'].'</p>

	<p><h2>Lights (brakes, headlights, indicator)</h2></p>
	<p>'.$this->config->item('passfail')[$getfulldata[0]['lights']].'</p>';

	if(isset($getfulldata[0]['lights_image']) && $getfulldata[0]['lights_image'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['lights_image'].'"> </p>';
	}
	if(isset($getfulldata[0]['lights_image2']) && $getfulldata[0]['lights_image2'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['lights_image2'].'"> </p>';
	}
	if(isset($getfulldata[0]['lights_image3']) && $getfulldata[0]['lights_image3'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['lights_image3'].'"> </p>';
	}
	if(isset($getfulldata[0]['lights_image4']) && $getfulldata[0]['lights_image4'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['lights_image4'].'"> </p>';
	}
	if(isset($getfulldata[0]['lights_image5']) && $getfulldata[0]['lights_image5'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['lights_image5'].'"> </p>';
	}
	if(isset($getfulldata[0]['lights_image6']) && $getfulldata[0]['lights_image6'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['lights_image6'].'"> </p>';
	}
	if(isset($getfulldata[0]['lights_image7']) && $getfulldata[0]['lights_image7'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['lights_image7'].'"> </p>';
	}
	if(isset($getfulldata[0]['lights_image8']) && $getfulldata[0]['lights_image8'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['lights_image8'].'"> </p>';
	}
	if(isset($getfulldata[0]['lights_image9']) && $getfulldata[0]['lights_image9'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['lights_image9'].'"> </p>';
	}
	if(isset($getfulldata[0]['lights_image10']) && $getfulldata[0]['lights_image10'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['lights_image10'].'"> </p>';
	}
		
	$html 		=	$html.'<p>'.$getfulldata[0]['lights_faults'].'</p>

	<p><h2>Windscreen and wipers</h2></p>
	<p>'.$this->config->item('passfail')[$getfulldata[0]['windscreen']].'</p>';

	if(isset($getfulldata[0]['windscreen_image']) && $getfulldata[0]['windscreen_image'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['windscreen_image'].'"> </p>';
	}
	if(isset($getfulldata[0]['windscreen_image2']) && $getfulldata[0]['windscreen_image2'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['windscreen_image2'].'"> </p>';
	}
	if(isset($getfulldata[0]['windscreen_image3']) && $getfulldata[0]['windscreen_image3'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['windscreen_image3'].'"> </p>';
	}
	if(isset($getfulldata[0]['windscreen_image3']) && $getfulldata[0]['windscreen_image3'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['windscreen_image3'].'"> </p>';
	}
	if(isset($getfulldata[0]['windscreen_image4']) && $getfulldata[0]['windscreen_image4'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['windscreen_image4'].'"> </p>';
	}
	if(isset($getfulldata[0]['windscreen_image5']) && $getfulldata[0]['windscreen_image5'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['windscreen_image5'].'"> </p>';
	}
	if(isset($getfulldata[0]['windscreen_image6']) && $getfulldata[0]['windscreen_image6'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['windscreen_image6'].'"> </p>';
	}
	if(isset($getfulldata[0]['windscreen_image7']) && $getfulldata[0]['windscreen_image7'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['windscreen_image7'].'"> </p>';
	}
	if(isset($getfulldata[0]['windscreen_image8']) && $getfulldata[0]['windscreen_image8'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['windscreen_image8'].'"> </p>';
	}
	if(isset($getfulldata[0]['windscreen_image9']) && $getfulldata[0]['windscreen_image9'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['windscreen_image9'].'"> </p>';
	}
	if(isset($getfulldata[0]['windscreen_image10']) && $getfulldata[0]['windscreen_image10'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['windscreen_image10'].'"> </p>';
	}
		
	$html 		=	$html.'<p>'.$getfulldata[0]['windscreen_faults'].'</p>

	<p><h2>Body (including branding)</h2></p>
	<p>'.$this->config->item('passfail')[$getfulldata[0]['body']].'</p>';

	if(isset($getfulldata[0]['body_image']) && $getfulldata[0]['body_image'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['body_image'].'"> </p>';
	}
	if(isset($getfulldata[0]['body_image2']) && $getfulldata[0]['body_image2'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['body_image2'].'"> </p>';
	}
	if(isset($getfulldata[0]['body_image3']) && $getfulldata[0]['body_image3'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['body_image3'].'"> </p>';
	}
	if(isset($getfulldata[0]['body_image3']) && $getfulldata[0]['body_image3'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['body_image3'].'"> </p>';
	}
	if(isset($getfulldata[0]['body_image4']) && $getfulldata[0]['body_image4'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['body_image4'].'"> </p>';
	}
	if(isset($getfulldata[0]['body_image5']) && $getfulldata[0]['body_image5'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['body_image5'].'"> </p>';
	}
	if(isset($getfulldata[0]['body_image6']) && $getfulldata[0]['body_image6'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['body_image6'].'"> </p>';
	}
	if(isset($getfulldata[0]['body_image7']) && $getfulldata[0]['body_image7'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['body_image7'].'"> </p>';
	}
	if(isset($getfulldata[0]['body_image8']) && $getfulldata[0]['body_image8'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['body_image8'].'"> </p>';
	}
	if(isset($getfulldata[0]['body_image9']) && $getfulldata[0]['body_image9'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['body_image9'].'"> </p>';
	}
	if(isset($getfulldata[0]['body_image10']) && $getfulldata[0]['body_image10'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['body_image10'].'"> </p>';
	}
		
	$html 		=	$html.'<p>'.$getfulldata[0]['body_faults'].'</p>

	<p><h2>Door Locks</h2></p>
	<p>'.$this->config->item('passfail')[$getfulldata[0]['doorlocks']].'</p>';

	if(isset($getfulldata[0]['doorlocks_image']) && $getfulldata[0]['doorlocks_image'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['doorlocks_image'].'"> </p>';
	}
	if(isset($getfulldata[0]['doorlocks_image2']) && $getfulldata[0]['doorlocks_image2'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['doorlocks_image2'].'"> </p>';
	}
		
	$html 		=	$html.'<p>'.$getfulldata[0]['doorlocks_faults'].'</p>

	<p><h2>Equipment and material: secured to roof</h2></p>
	<p>'.$this->config->item('passfail')[$getfulldata[0]['equipment']].'</p>';

	if(isset($getfulldata[0]['equipment_image']) && $getfulldata[0]['equipment_image'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['equipment_image'].'"> </p>';
	}
	if(isset($getfulldata[0]['equipment_image2']) && $getfulldata[0]['equipment_image2'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['equipment_image2'].'"> </p>';
	}
	if(isset($getfulldata[0]['equipment_image3']) && $getfulldata[0]['equipment_image3'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['equipment_image3'].'"> </p>';
	}
	if(isset($getfulldata[0]['equipment_image4']) && $getfulldata[0]['equipment_image4'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['equipment_image4'].'"> </p>';
	}
	if(isset($getfulldata[0]['equipment_image5']) && $getfulldata[0]['equipment_image5'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['equipment_image5'].'"> </p>';
	}
	if(isset($getfulldata[0]['equipment_image6']) && $getfulldata[0]['equipment_image6'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['equipment_image6'].'"> </p>';
	}
	if(isset($getfulldata[0]['equipment_image7']) && $getfulldata[0]['equipment_image7'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['equipment_image7'].'"> </p>';
	}
	if(isset($getfulldata[0]['equipment_image8']) && $getfulldata[0]['equipment_image8'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['equipment_image8'].'"> </p>';
	}
	if(isset($getfulldata[0]['equipment_image9']) && $getfulldata[0]['equipment_image9'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['equipment_image9'].'"> </p>';
	}
	if(isset($getfulldata[0]['equipment_image10']) && $getfulldata[0]['equipment_image10'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['equipment_image10'].'"> </p>';
	}
		
	$html 		=	$html.'<p>'.$getfulldata[0]['equipment_faults'].'</p>

	<p><h2>Warning flag available</h2></p>
	<p>'.$this->config->item('passfail')[$getfulldata[0]['warningflag']].'</p>';

	if(isset($getfulldata[0]['warningflag_image']) && $getfulldata[0]['warningflag_image'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['warningflag_image'].'"> </p>';
	}
	if(isset($getfulldata[0]['warningflag_image2']) && $getfulldata[0]['warningflag_image2'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['warningflag_image2'].'"> </p>';
	}
	if(isset($getfulldata[0]['warningflag_image3']) && $getfulldata[0]['warningflag_image3'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['warningflag_image3'].'"> </p>';
	}
	if(isset($getfulldata[0]['warningflag_image4']) && $getfulldata[0]['warningflag_image4'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['warningflag_image4'].'"> </p>';
	}
	if(isset($getfulldata[0]['warningflag_image5']) && $getfulldata[0]['warningflag_image5'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['warningflag_image5'].'"> </p>';
	}
	if(isset($getfulldata[0]['warningflag_image6']) && $getfulldata[0]['warningflag_image6'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['warningflag_image6'].'"> </p>';
	}
	if(isset($getfulldata[0]['warningflag_image7']) && $getfulldata[0]['warningflag_image7'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['warningflag_image7'].'"> </p>';
	}
	if(isset($getfulldata[0]['warningflag_image8']) && $getfulldata[0]['warningflag_image8'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['warningflag_image8'].'"> </p>';
	}
	if(isset($getfulldata[0]['warningflag_image9']) && $getfulldata[0]['warningflag_image9'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['warningflag_image9'].'"> </p>';
	}
	if(isset($getfulldata[0]['warningflag_image10']) && $getfulldata[0]['warningflag_image10'] != ''){
		$html 	=	$html. '<p> <img height="150px" widht="150px" src="'.$pdfurl.$getfulldata[0]['warningflag_image10'].'"> </p>';
	}
		
	$html 		=	$html.'<p>'.$getfulldata[0]['warningflag_faults'].'</p>
	
		
</body>';
				
				$filePath = FCPATH.'assets/pdf/';
		        $pdfFilePath = 'vehiclechecklist-'.$this->input->post("viewid").'.pdf';
		        $dompdf = new DOMPDF();
    			$dompdf->loadHtml($html);
    			$dompdf->setPaper('A2', 'portrait');
    			$dompdf->render();
    			$output = $dompdf->output();
    			file_put_contents($filePath.$pdfFilePath, $output);
				
                $data['pdfurl']				    =   (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/assets/pdf/$pdfFilePath";
				$data['message']				=	"Record downloaded successfully.";
			}
			else{
				$data['message']				=	"Record was not found.";
			}			
		}
		else{
			$data['message']					=	"Mandatory fields are missing";
		}
		echo json_encode($data);
	}

	public function imageupload(){
		die;
		$data 					=	array();
		if($this->input->post("imagefile") != ''){
			$imagefile 			=	$this->input->post("imagefile") ;			
			define('UPLOAD_DIR', './images/');
			$image_parts 		= 	explode(";base64,", $imagefile);
			$image_base64 		= 	base64_decode($image_parts[1]);
			$file 				= 	UPLOAD_DIR . uniqid() . '.png';
			file_put_contents($file, $image_base64);
			if(isset($file)){
				$filename 		=	str_replace("./images/","",$file);			
				$data['imagefilename'] 	= 	$filename;
			}
		}
		echo json_encode($data);
	}

	public function significantrisks_temp_list()
	{
		die;
		$data=array();	
		if($this->input->post("user_id") != '')
		{
			$data["title"] 								= 	'Significant Risk List';
			$user_id 										= 	$this->input->post("user_id");
			$getfulldata4 									=	$this->adminmodel->getdata_significantrisks_temp($user_id);
			for($i=0; $i < count($getfulldata4); $i++){
				$data["significantrisks"][$i]["id"]			=	$getfulldata4[$i]['id'];	
				$data["significantrisks"][$i]["name"]		=	$getfulldata4[$i]['name'];
				$data["significantrisks"][$i]["ratingname"]	=	$getfulldata4[$i]['ratingname'];	
			}
		}
		else{
			$data['message']="Mandatory fields are missing";
		}
		echo json_encode($data);
	}

	public function significantrisks_temp_insert()
	{
		die;
		$data=array();
		if($this->input->post("user_id") != '' && $this->input->post("name") != '' && $this->input->post("rating") != '')
		{	
			$requestdata	=	$this->input->post();
			$statusvalue	=	$this->adminmodel->action("significantrisks_temp",$requestdata);
			if($statusvalue > 0)
				$data['message']="Inserted successfully";
			else
				$data['message']="Data has some error.";


			$user_id 										= 	$this->input->post("user_id");
			$getfulldata4 									=	$this->adminmodel->getdata_significantrisks_temp($user_id);
			for($i=0; $i < count($getfulldata4); $i++){
				$data["significantrisks"][$i]["id"]			=	$getfulldata4[$i]['id'];	
				$data["significantrisks"][$i]["name"]		=	$getfulldata4[$i]['name'];
				$data["significantrisks"][$i]["ratingname"]	=	$getfulldata4[$i]['ratingname'];	
			}

		}
		else{
			$data['message']="Mandatory fields are missing";
		}
		echo json_encode($data);
	}

	public function significantrisks_temp_delete()
	{
		die;
		$data=array();	
		if($this->input->post("user_id") != '' && $this->input->post("deleteid") != '')
		{
			$data["title"] 								= 	'Significant Risks Delete';
			$requestdata								=	$this->input->post();
			$statusvalue								=	$this->adminmodel->delete("significantrisks_temp",$requestdata);	
			if($statusvalue > 0)
				$data['message']="Deleted successfully";
			else
				$data['message']="Data has some error";

			$user_id 										= 	$this->input->post("user_id");
			$getfulldata4 									=	$this->adminmodel->getdata_significantrisks_temp($user_id);
			for($i=0; $i < count($getfulldata4); $i++){
				$data["significantrisks"][$i]["id"]			=	$getfulldata4[$i]['id'];	
				$data["significantrisks"][$i]["name"]		=	$getfulldata4[$i]['name'];
				$data["significantrisks"][$i]["ratingname"]	=	$getfulldata4[$i]['ratingname'];	
			}


		}
		else{
			$data['message']="Mandatory fields are missing";
		}	
		echo json_encode($data);
	}

	public function significantrisks_temp_clear()
	{
		die;
		$data=array();	
		if($this->input->post("user_id") != '')
		{
			$data["title"] 								= 	'Significant Risks Clear';
			$requestdata								=	$this->input->post();
			$statusvalue								=	$this->adminmodel->clear("significantrisks_temp",$requestdata);	
			if($statusvalue > 0)
				$data['message']="Deleted successfully";
			else
				$data['message']="Data has some error";
		}
		else{
			$data['message']="Mandatory fields are missing";
		}	
		echo json_encode($data);
	}

	public function siterisk_options()
	{
		die;
		$data=array();	
		$data["title"] 								= 	'Who May Be Harmed?';				
		$getfulldata 								=	$this->adminmodel->getfulldata("whomaybeharmed");
		for($i=0; $i < count($getfulldata); $i++){
			$data["whomaybeharmed"][$i]["id"]		=	$getfulldata[$i]['id'];	
			$data["whomaybeharmed"][$i]["name"]		=	$getfulldata[$i]['name'];
		}
		
		$data["title2"] 							= 	'Personal Protective Equipment';			
		$getfulldata 								=	$this->adminmodel->getfulldata("personalequipment");
		for($i=0; $i < count($getfulldata); $i++){
			$data["personalequipment"][$i]["id"]	=	$getfulldata[$i]['id'];	
			$data["personalequipment"][$i]["name"]	=	$getfulldata[$i]['name'];	
		}

		$data["title3"] 							= 	'Risk:';
		$getfulldata 								=	$this->adminmodel->getfulldata("risk");
		for($i=0; $i < count($getfulldata); $i++){
			$data["risk"][$i]["id"]	=	$getfulldata[$i]['id'];	
			$data["risk"][$i]["name"]	=	$getfulldata[$i]['name'];	
		}


		echo json_encode($data);
	}

	public function siterisklist()
	{
		die;
		$data=array();	
		if($this->input->post("user_id") != '')
		{
			$data["title"] 								= 	'Site Risk Assessment';
			$data["history_title"] 						= 	'History';
			$requestdata 								= 	$this->input->post();								
			$getfulldata								= 	$this->adminmodel->getfulldata("siterisk_assessment",$requestdata);

			for($i=0; $i < count($getfulldata); $i++){
				$data["siterisk"][$i]['id']					=	$getfulldata[$i]['id'];
				$data["siterisk"][$i]['createddate']		=	$getfulldata[$i]['createddate'];
				$data["siterisk"][$i]['companyname']		=	$getfulldata[$i]['companyname'];	
			}
		}
		else{
			$data['message']="Mandatory fields are missing";
		}
		echo json_encode($data);
	}	

	public function siterisk_insert()
	{
		die;
		$data=array();
		if($this->input->post("user_id") != '' && $this->input->post("companyname") != '' && $this->input->post("task") != '')
		{	
			$requestdata		=	$this->input->post();
			$statusvalue		=	$this->adminmodel->action("siterisk_assessment",$requestdata);
			if($statusvalue > 0){
				$getfulldata	= 	$this->adminmodel->getfulldata("significantrisks_temp",$requestdata);
				for($i=0; $i < count($getfulldata); $i++){
					$temp_requestdata["siterisk_id"]		=	$statusvalue;
					$temp_requestdata["name"]				=	$getfulldata[$i]['name'];
					$temp_requestdata["rating"]				=	$getfulldata[$i]['rating'];	

					$this->adminmodel->action("significantrisks",$temp_requestdata);
				}

				$data['message']="Inserted successfully";

				$requestdata2['user_id']					=	$this->input->post("user_id");
				$statusvalue2								=	$this->adminmodel->clear("significantrisks_temp",$requestdata2);
				$data['temp_clear']=$statusvalue2;
			}
			else{
				$data['message']="Data has some error.";
			}

		}
		else{
			$data['message']="Mandatory fields are missing";
		}
		echo json_encode($data);
	}

	public function siterisk_delete()
	{
		die;
		$data=array();	
		if($this->input->post("user_id") != '' && $this->input->post("deleteid") != '')
		{
			$data["title"] 								= 	'Site Risk Assessment Delete';
			$requestdata								=	$this->input->post();
			$statusvalue								=	$this->adminmodel->delete("siterisk_assessment",$requestdata);	

			$this->adminmodel->clear("significantrisks_temp",$requestdata);

			if($statusvalue > 0)
				$data['message']="Deleted successfully";
			else
				$data['message']="Data has some error";
		}
		else{
			$data['message']="Mandatory fields are missing";
		}	
		echo json_encode($data);
	}

	public function siterisk_view()
	{
		die;
		$data=array();
		if($this->input->post("user_id") != '' && $this->input->post("viewid") != '')
		{
			$data["title"] 						= 	'Site Risk Assessment';
			$requestdata 						= 	$this->input->post();								
			$getfulldata						= 	$this->adminmodel->getfulldata("siterisk_assessment",$requestdata);
			if(count($getfulldata)){	
				$data['companyname'] 			= 	$getfulldata[0]['companyname'];
				$data['task'] 					= 	$getfulldata[0]['task'];
				$data['location'] 				= 	$getfulldata[0]['location'];
				
				$data['controlmeasures_title'] 	= 	"Control Measures";
				$data['controlmeasures']		= 	$getfulldata[0]['controlmeasures'];

				$data['information_title'] 	= 	"Information, Instruction & Training";
				$data['information']			= 	$getfulldata[0]['information'];

				$harmed							= 	$getfulldata[0]['harmed'];
				$data['whomaybeharmed_title'] 	= 	"Who May Be Harmed?";
				$requestdata2['harmed_id'] 		= 	explode(',', $harmed);
				$getfulldata2 					=	$this->adminmodel->getfulldata("whomaybeharmed",$requestdata2);
				for($i=0; $i < count($getfulldata2); $i++){
					$data["whomaybeharmed"][$i]["id"]		=	$getfulldata2[$i]['id'];	
					$data["whomaybeharmed"][$i]["name"]		=	$getfulldata2[$i]['name'];	
				}				

				$personalequipment				= 	$getfulldata[0]['personalequipment'];
				$data['personalequipment_title'] 		= 	"Personal Protective Equipment";
				$requestdata3['personalequipment_id'] 	= 	explode(',', $personalequipment);
				$getfulldata3 							=	$this->adminmodel->getfulldata("personalequipment",$requestdata3);
				for($i=0; $i < count($getfulldata3); $i++){
					$data["personalequipment"][$i]["id"]		=	$getfulldata3[$i]['id'];	
					$data["personalequipment"][$i]["name"]		=	$getfulldata3[$i]['name'];	
				}

				$data['significantrisks_title'] = 	"Significant Risks";
				$viewid 						= 	$this->input->post("viewid");
				$getfulldata4 					=	$this->adminmodel->getdata_significantrisks($viewid);
				for($i=0; $i < count($getfulldata4); $i++){
					$data["significantrisks"][$i]["id"]			=	$getfulldata4[$i]['id'];	
					$data["significantrisks"][$i]["name"]		=	$getfulldata4[$i]['name'];
					$data["significantrisks"][$i]["ratingname"]	=	$getfulldata4[$i]['ratingname'];	
				}
			}
			else{
				$data['message']				=	"Record was not found.";
			}			
		}
		else{
			$data['message']					=	"Mandatory fields are missing";
		}
		echo json_encode($data);
	}

	public function siterisk_pdf()
	{
		die;
		$pdfurl = $_SERVER['DOCUMENT_ROOT'].'/images/';
		$data=array();
		if($this->input->post("user_id") != '' && $this->input->post("viewid") != '')
		{

			$data["title"] 			= 	'Site Risk Assessment';
			$requestdata 			= 	$this->input->post();								
			$getfulldata			= 	$this->adminmodel->getfulldata("siterisk_assessment",$requestdata);
			
			if(count($getfulldata)){
				
				$html 				=
'<!DOCTYPE html>
<html>
<head>
<title>Site Risk Assessment</title>
</head>

<body>
	<img src="'.$pdfurl.'pribLogo.png">
	<h1>Site Risk Assessment</h1>
	<p>'.$getfulldata[0]['companyname'].'</p>
	<p>'.$getfulldata[0]['task'].'</p>
	<p>'.$getfulldata[0]['location'].'</p>';

	$html 							=	$html.'<p><h2>Who May Be Harmed?<h2></p>';
	$harmed_data					=	'';
	$requestdata2['harmed_id'] 		= 	explode(',', $getfulldata[0]['harmed']);
	$getfulldata2 					=	$this->adminmodel->getfulldata("whomaybeharmed",$requestdata2);
	for($i=0; $i < count($getfulldata2); $i++){		
		$harmed_data				=	$harmed_data.'<p>'.$getfulldata2[$i]['name'].'</p>';
	}	
	$html 							=	$html.$harmed_data;

	$html 							=	$html.'<p><h2>Significant Risks</h2></p>';
	$significant_data				=	'<table>';
	$viewid 						= 	$this->input->post("viewid");
	$getfulldata4 					=	$this->adminmodel->getdata_significantrisks($viewid);
	// for($i=0; $i < count($getfulldata4); $i++){
	// 	$name 						=	$getfulldata4[$i]['name'];
	// 	$significant_data			=	$significant_data.'<tr><td>'.$name.'</td><td>'.$getfulldata4[$i]['ratingname']'</td></table>';
	// }	
	$significant_data				=	$significant_data.'</table>';
		
	$html 							=	$html.'<p><h2>Control Measures</h2></p>';
	$html 							=	$html.'<p><h2>'.$getfulldata[0]['controlmeasures'].'</h2></p>';

	$html 							=	$html.'<p><h2>Information, Instruction & Training</h2></p>';
	$html 							=	$html.'<p><h2>'.$getfulldata[0]['information'].'</h2></p>';
	
		
	$html 							=	$html.'<p><h2>Personal Protective Equipment</h2></p>';
	$equipment_data					=	'';
	$requestdata3['personalequipment_id'] 	= 	explode(',', $getfulldata[0]['personalequipment']);
	$getfulldata3 							=	$this->adminmodel->getfulldata("personalequipment",$requestdata3);
	for($i=0; $i < count($getfulldata3); $i++){
		$equipment_data				=	$equipment_data.'<p>'.$getfulldata3[$i]['name'].'</p>';
	}
	$html 							=	$html.$equipment_data;


		
	$html 							=	$html.'</body>';


				$filePath = FCPATH.'assets/pdf/';
		        $pdfFilePath = 'siteriskassessment-'.$this->input->post("viewid").'.pdf';
		        $dompdf = new DOMPDF();
    			$dompdf->loadHtml($html);
    			$dompdf->setPaper('A2', 'portrait');
    			$dompdf->render();
    			$output = $dompdf->output();
    			file_put_contents($filePath.$pdfFilePath, $output);

				$data['pdfurl']				    =   (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/assets/pdf/$pdfFilePath";

				$data['message']				=	"Record downloaded successfully.";
			}
			else{
				$data['message']				=	"Record was not found.";
			}			
		}
		else{
			$data['message']					=	"Mandatory fields are missing";
		}
		echo json_encode($data);
	}

	public function advertise_contactus()
	{
		die;
		$data=array();	
		if($this->input->post("name") != '' && $this->input->post("email") != '' && $this->input->post("message") != '')
		{		

			//Email send
			$getfulldata2 						=	$this->adminmodel->getfulldata("advertise_settings");	
			$contactus_email 					= 	$getfulldata2[0]['email'];
			// $this->load->library('parser');

			// $config = array();
	        // $config['useragent']           	= "CodeIgniter";
	        // $config['mailpath']            	= "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
	        $config['mailpath'] 				= '/usr/sbin/sendmail';
	        // $config['protocol']            	= "mail";
	        $config['protocol']            		= "sendmail";
	        // $config['smtp_host']           	= "localhost";
	        // $config['smtp_port']           	= "25";
	        $config['mailtype'] 				= 'html';
	        // $config['charset']  				= 'utf-8';
	        $config['charset']  				= 'iso-8859-1';
	        // $config['newline']  				= "\r\n";
	        $config['wordwrap'] 				= TRUE;

	        $this->load->library('email');
	        $this->email->initialize($config);
			$this->email->set_newline("\r\n");
			
			$name 		= $this->input->post("name");
			$email 		= $this->input->post("email");
			$message 	= $this->input->post("message");
			
	        // $this->email->from('info@itfhrm.com', 'Message from App Advertise Contactus');
	        $this->email->from('donotreply@articulateit.co.za', 'Message from APP Plumber ArticulateIT Contact Us');
	        $this->email->to($contactus_email);
	        $this->email->subject('Message from App Advertise Contactus');		
			$this->email->message(
			"Good day <br/><br/> You have recieved a message from ".$name." regarding your Advertise Contactus, the user details are: <br/><br/> Email Address: ".$email." <br/><br/> Message :".$message."<br/><br/> Note: <br/> This is an auto-generated email.");
			$this->email->send();			
						
			$data['email_status'] = $this->email->print_debugger();

			//Email close

			$insertdata=array(
					"name" => $this->input->post("name"),
					"email" => $this->input->post("email"),
					"message" => $this->input->post("message"),
					"createddate" => date("Y-m-d h:i:s")
					);
			$statusvalue=$this->adminmodel->insertdata("advertise_contactus",$insertdata);
			$data['message']="Inserted successfully";
			$data['message']="Thanks for reaching us. We'll get back to you soon.";

		}
		else{
			$data['message']="Mandatory fields are missing";
		}
		echo json_encode($data);
	}

	public function advertise_address()
	{
		die;
		$pageid 		= $this->config->item('pagesid')['32'];
		$pagename 		= $this->adminmodel->getpageid_api("pages",$pageid);

		$data=array();
		$data["title"] 						= 	'Contact Us';
		$topbanner = $this->adminmodel->getfulldata_api("banner",'top',$pageid);
		for($i=0; $i < count($topbanner); $i++){			
			$data['topbanner'][$i]['image']		=base_url().'./images/'.$topbanner[$i]['image'];
			$data['topbanner'][$i]['link']		=$topbanner[$i]['link'];
			$data['topbanner'][$i]['imageid']	=$topbanner[$i]['id'];
			$data['topbanner'][$i]['pageid']	=$pageid;
		}
		$data["title_content1"] 			= 	'Want to advertise with APP Plumber or simply just ask a question?';	
		$data["title_content2"] 			= 	'Leave us a message and we`ll get in contact with you as soon as possible.';
		$data["contact_title"] 				= 	'Contact Details';
		$data["address_title"] 				= 	'Physical Address:';				
		$getfulldata 						=	$this->adminmodel->getfulldata("advertise_address");	
		$data["telephone"] 					= 	$getfulldata[0]['telephone'];
		$data["fax"] 						= 	$getfulldata[0]['fax'];
		$data["email"] 						= 	$getfulldata[0]['email'];
		
		$data["address"] 					= 	str_replace("\r\n","</br>",$getfulldata[0]['address']);
		echo json_encode($data);
	
	}

	public function productguide_category()
	{
		die;
		$data 								=	array();
		$requestdata 						=	array();
		$data["title"] 						= 	'Product and Installation Guides';	
		$requestdata['orderby_position'] 	= 	'1';			
		$getfulldata 						=	$this->adminmodel->getfulldata("productguides",['orderby_position' => '1', 'extras' => 'pagetrue']);	
		
		for($i=0; $i < count($getfulldata); $i++){
			$data["productguide"][$i]["id"]			=	$getfulldata[$i]['id'];	
			$data["productguide"][$i]["content"]	=	$getfulldata[$i]['content'];
			$data["productguide"][$i]["image"]	=	base_url().'./images/'.$getfulldata[$i]['image'];	
		}
		echo json_encode($data);
	}

	public function productguide_subcategory()
	{
		die;
		$data 								=	array();
		$requestdata 						=	array();
		$data["title"] 						= 	'Product and Installation Guides';
		$requestdata 						= 	$this->input->post();	
		$requestdata['orderby_position'] 	= 	'1';
		$requestdata['pagename'] 			= 	'productguide_subcategory';
		$requestdata['extras'] 				= 	'pagetrue';	
		$getfulldata 						=	$this->adminmodel->getfulldata("productguidessection1",$requestdata);
		// print_r($this->db->last_query());die;
		for($i=0; $i < count($getfulldata); $i++){			
			$data["productguide"][$i]["id"]			=	$getfulldata[$i]['id'];	
			$data["productguide"][$i]["content"]	=	$getfulldata[$i]['content'];
			$data["productguide"][$i]["image"]	=	base_url().'./images/'.$getfulldata[$i]['image'];	
			$productguidesid						=	$getfulldata[$i]['productguidesid'];
			$getfulldata_pg 						=	$this->adminmodel->getdata_productguidessection1('',$productguidesid);
			for($j=0; $j < count($getfulldata_pg); $j++){
				$data["productguide"][$i]["category"]	=	$getfulldata_pg[$j]['pgcontent'];
			}
		}
		echo json_encode($data);
	}
	public function productguide_innersubcategory()
	{
		die;
		$data 								=	array();
		$requestdata 						=	array();
		$data["title"] 						= 	'Product and Installation Guides';
		// $requestdata['orderby_position'] 	= 	'1';
		$requestdata 						= 	$this->input->post();	
		$getfulldata 						=	$this->adminmodel->getdata_productguidessection2("",$requestdata['innersubid'], ['pagetype' => 'apiproductguide_innersubcategory']);

		for($i=0; $i < count($getfulldata); $i++){

			$data["productguide"][$i]["id"]				=	$getfulldata[$i]['id'];	
			$data["productguide"][$i]["content"]		=	$getfulldata[$i]['content'];
			if ($getfulldata[$i]['description'] !='') {
				$data["productguide"][$i]["description"]	=	$getfulldata[$i]['description'];
			}else{
				$data["productguide"][$i]["description"]	=	'';
			}
			
			$productguidesid							=	$getfulldata[$i]['productguidessection1id'];

			if ($getfulldata[$i]['file'] !='') {
				$data["productguide"][$i]["file"]		=	base_url().'./images/'.$getfulldata[$i]['file'];
			}else{
				$data["productguide"][$i]["file"]		=	'';
			}
			if ($getfulldata[$i]['image'] !='') {
				$data["productguide"][$i]["image"]		=	base_url().'./images/'.$getfulldata[$i]['image'];
			}else{
				$data["productguide"][$i]["image"]		=	'';
			}
			if ($getfulldata[$i]['feat_file'] !='') {
				$data["productguide"][$i]["feat_file"]	=	base_url().'./images/'.$getfulldata[$i]['feat_file'];
			}else{
				$data["productguide"][$i]["feat_file"]	=	'';
			}
			
			$data["productguide"][$i]["type"]			=	$getfulldata[$i]['type'];
			$data["productguide"][$i]["type_words"]		=	$this->config->item('magazinetype')[$getfulldata[$i]['type']];

			$getfulldata_pg 						=	$this->adminmodel->getdata_productguidessection3_api('',$productguidesid);
			
			for($j=0; $j < count($getfulldata_pg); $j++){
				$data["productguide"][$i]["category"]	=	$getfulldata_pg[$j]['pgcontent'];
			}
		}
		echo json_encode($data);
	}
	public function productguide_innersubcategory1()
	{
		die;
		$data 								=	array();
		$requestdata 						=	array();
		$data["title"] 						= 	'Product and Installation Guides';
		// $requestdata['orderby_position'] 	= 	'1';
		$requestdata 						= 	$this->input->post();	
		$getfulldata 						=	$this->adminmodel->getdata_productguidessection3_api("",$requestdata['id']);
		for($i=0; $i < count($getfulldata); $i++){			
			$data["productguide"][$i]["id"]			=	$getfulldata[$i]['id'];	
			$data["productguide"][$i]["content"]	=	$getfulldata[$i]['content'];
			$data["productguide"][$i]["body"]		=	$getfulldata[$i]['body'];
			$data["productguide"][$i]["pgcontent"]	=	$getfulldata[$i]['pgcontent'];
			$data["productguide"][$i]["position"]	=	$getfulldata[$i]['position'];
			$data["productguide"][$i]["published"]	=	$getfulldata[$i]['published'];
			if ($getfulldata[$i]['file'] !='') {
				$file = base_url().'images/'.$getfulldata[$i]['file'].'';
			}

			$data["productguide"][$i]["file"]		=	isset($file) ? $file : '';
			$data["productguide"][$i]["type"]		=	$getfulldata[$i]['type'];
			$data["productguide"][$i]["type_word"]	=	$this->config->item('magazinetype')[$getfulldata[$i]['type']];
			if ($getfulldata[$i]['feat_file'] !='') {
				$data["productguide"][$i]["feat_file"]	=	base_url().'images/'.$getfulldata[$i]['feat_file'].'';
			}else{
				$data["productguide"][$i]["feat_file"]	=	'';
			}
			$productguidesid						=	$getfulldata[$i]['productguidesid'];
			$getfulldata_pg 						=	$this->adminmodel->getdata_productguidessection3_api('',$productguidesid);
			for($j=0; $j < count($getfulldata_pg); $j++){
				$data["productguide"][$i]["category"]	=	$getfulldata_pg[$j]['pgcontent'];
			} 
		}
		echo json_encode($data);
	}

	public function fileupload($data = []){
		die;

		define('UPLOAD_DIR', './images/');
		$base64files = $data['files'];
		$file_name 	 = $data['file_name'];
		$path 		 = UPLOAD_DIR;

		// $file_size	=  $base64files['image']['size'];
    	$files		=  explode(',', $base64files);
    	$countfiles = count($files);

		
		if ($countfiles > 1) {
			$file_names = explode(',', $file_name);
			for($i=0;$i<$countfiles;$i++){
				
				$base64		= $files[$i];
				$file_name 	= $file_names[$i];
	            $extension 	= explode('.', $file_name)[1];
	            $image 		= base64_decode($base64);
	            $image_name = md5(uniqid(rand(), true).$i);
	            $filename 	= $image_name . '.' . $extension;
				$filearray[] 	= $filename;
	            file_put_contents($path . $filename, $image);
			}
		}
		else{

			$base64		= $base64files;
			$extension 	= explode('.', $file_name)[1];
	        $image 		= base64_decode($base64);
	        $image_name = md5(uniqid(rand(), true));
	        $filename 	= $image_name . '.' . $extension;
	        $filearray 	= $filename;

			file_put_contents($path . $filename, $image);
		}
		if (is_array($filearray) && (count($filearray) > 1)) {
			$file[] = implode(",",$filearray);
		}else{
			$file[] = $filearray;
		}
		return $file;
	}

	public function Update_API()
	{
		die;
		$data=array();
		if($this->input->post("appversion") =='1.11')
		{
			$data["status"]="1";
			$data["message"]="Please Update your app";
			$data["link"]	= "https://play.google.com/store/apps/details?id=com.app.plumber";
			
		} else if($this->input->post("appversion") =='1.10')
		{
			$data["status"]="1";
			$data["message"]="Please Update your app";
			$data["link"]	= "https://play.google.com/store/apps/details?id=com.app.plumber";
			
		} else if($this->input->post("appversion") =='1.9')
		{
			$data["status"]="1";
			$data["message"]="Please Update your app";
			$data["link"]	= "https://play.google.com/store/apps/details?id=com.app.plumber";
			
		} else if($this->input->post("appversion") =='1.8')
		{
			$data["status"]="1";
			$data["message"]="Please Update your app";
			$data["link"]	= "https://play.google.com/store/apps/details?id=com.app.plumber";
			
		} else if($this->input->post("appversion") =='1.7')
		{
			$data["status"]="1";
			$data["message"]="Please Update your app";
			$data["link"]	= "https://play.google.com/store/apps/details?id=com.app.plumber";
			
		} 
		else if($this->input->post("appversion") =='1.6')
		{
			$data["status"]="1";
			$data["message"]="Please Update your app";
			$data["link"]	= "https://play.google.com/store/apps/details?id=com.app.plumber";
			
		} 
		else if($this->input->post("appversion") =='1.5')
		{
			$data["status"]="1";
			$data["message"]="Please Update your app";
			$data["link"]	= "https://play.google.com/store/apps/details?id=com.app.plumber";
			
		} 
		else if($this->input->post("appversion") =='1.4')
		{
			$data["status"]="1";
			$data["message"]="Please Update your app";
			$data["link"]	= "https://play.google.com/store/apps/details?id=com.app.plumber";
			
		} else{
			$data["status"]="0";
		
			$data["message"]="Please Update your app";
			$data["link"]	= "https://play.google.com/store/apps/details?id=com.app.plumber";
			
		}
		echo json_encode($data);
				
		
		
	}
	public function banner_global()
	{
		die;
		$global 		= $this->config->item('pagesid')['33'];
		$globalpage 	= $this->adminmodel->getpageid_api("pages",$global);
		$data 			= array();
		$globaltopbanner = $this->adminmodel->getfulldata_api("banner",'top',$global);
		for($i=0; $i < count($globaltopbanner); $i++){			
			$data['globaltopbanner'][$i]['image']		=base_url().'./images/'.$globaltopbanner[$i]['image'];
			$data['globaltopbanner'][$i]['link']		=$globaltopbanner[$i]['link'];
			$data['globaltopbanner'][$i]['imageid']		=$globaltopbanner[$i]['id'];
			$data['globaltopbanner'][$i]['pageid']		=$global;
		}
		echo json_encode($data);
	}

}
?>
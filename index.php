<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

<link href="demo.css" rel="stylesheet" type="text/css" />
 <script type="text/javascript" src="jquery.js"></script>
 <script type="text/javascript" src="demo.js"></script>
  <script type="text/javascript" src="libs/php.full.commonjs.min.js"></script>
 
<link href="searchfield/searchfield.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="searchfield/searchfield.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script type="text/javascript">
  google.load("search", "1");
  google.load("jquery", "1.4.2");
  google.load("jqueryui", "1.7.2");
</script>

<title>Knowledge Search</title>
<?php header('Content-Type: text/html; charset=ISO-8859-1');?>
</head>

<body >
<?php 
// include 'formauto/form_semi_auto_site.php'; 

// variables globales de session

include "connectIKM.php";
include_once "DisplayClass.php";
 session_start();
 $sikm = new searchikm();
//$_SESSION["smarvin"]= serialize($sikm);
//$_SESSION["smarvin"]= $sikm;



$_SESSION["testglobal"]=0;

$_SESSION["tabpredicted"]=array();
$_SESSION["tabvalidated"]=array();
$_SESSION["suggested"]=array();
$_SESSION["tabunknown"]=array();
$_SESSION["tabininbited"]=array();
$_SESSION["tabhistory"]=array();
$_SESSION["tabcontext"]=array();

$_SESSION["locutization"]="ok";

?>
<div id="bodycadre">
    <div id="form_search_main">
      <span id="text_search_span"><input id="text_search" class="cl_search" name="search" type="text" />
    	  <span id="button_ok_span"><input id="button_ok" class="cl_button_ok" name="ok" type="button" value="search" /> </span> 
          <span id="display_option" > <img src="images/option.png" width="20" height="20" alt="options" /></span>

      </span>
       <span id="options">
       <fieldset class="smalltext">     
     	  <span><input id="but_sugestion" class="but_sugestion" name="sugestion" type="button" value="suggestions" size="10" /></span>         
     	  <!-- <span><input id="but_options" class="but_options" name="options" type="button" value="options" size="10" /></span> -->
          <span id="mixword"><input id="button_mixword" class="cl_button_mixword" name="mixword" type="button" value="mix query"  size="10" /> </span> 
          <span id="button_compare"><input id="button_compare" class="but_compare" name="compare" type="button" value="compare" size="10" /> </span> 
          <fieldset class="smalltext alignleft">    
        
             <legend >options : </legend>
             <span class="grouplang">  
              <label><input class="radio" type="radio" name="RadioGroupOpt" value="fr" id="RadioGroupOpt_0" checked/>fr</label>
              <label><input class="radio" type="radio" name="RadioGroupOpt" value="eng" id="RadioGroupOpt_1" />eng</label>
              </span>
              <label><input class="checkboxImages" type="checkbox" name="checkboxImages" value="img" id="checkboxImages" />Images</label>
              <label><input class="checkboxMarvin" type="checkbox" name="checkboxMarvin" value="marvin" id="checkboxMarvin" />Marvin</label>           
          </fieldset>
			<span class="groupmode">
           <fieldset class="smalltext alignleft">
             <legend >search mode : </legend>
              <label>
                <input type="radio" name="RadioGroupMode" value="1" id="RadioGroupMode_0"  checked/>
                Contexts</label>
              	<label>
                <input type="radio" name="RadioGroupMode" value="2" id="RadioGroupMode_1" />
                Evaluate</label>
         		<label>
                <input type="radio" name="RadioGroupMode" value="3" id="RadioGroupMode_2"/>
                Procedural</label>        
             </fieldset>   
  			</span>
	
       </fieldset>
       <!--<span class="smalltext"> Compatible Chrome et Explorer 9</span>-->
  
            </span>
    </div>
    
	<div id="form_search_option" class="cl_form_search_option">
	 
            <form action="" id="form" method="get" autocomplete="false">    
         
            <fieldset>
            <span id="close_options_menu"><img src="images/closered.gif" width="12" height="12" alt="close" /></span>  
             <legend class="legend"> Options : </legend>
        <!--
            <span class="grouplang">
             <fieldset class="smalltext alignleft">
            
             <legend >database : </legend>
              <label>
                <input class="radio" type="radio" name="RadioGroupLang" value="fr" id="RadioGroupLang_0" checked/>
                fr</label>
            
              <label>
                <input class="radio" type="radio" name="RadioGroupLang" value="fr2" id="RadioGroupLang_1" />
                fr2</label>            
              <label>
              
                <input class="radio" type="radio" name="RadioGroupLang" value="eng" id="RadioGroupLang_2" />
                eng</label>
             <label>
                <input class="radio" type="radio" name="RadioGroupLang" value="de" id="RadioGroupLang_3" />
                de</label>
                    
              <label>
                <input class="radio" type="radio" name="RadioGroupLang" value="es" id="RadioGroupLang_3" />
                es</label>
        <br />    
              <label>
                <input class="radio" type="radio" name="RadioGroupLang" value="it" id="RadioGroupLang_4" />
                it</label>
          
            <label>
                <input class="radio" type="radio" name="RadioGroupLang" value="med" id="RadioGroupLang_5" />
                medline</label>
          <label>
                <input class="radio" type="radio" name="RadioGroupLang" value="cancer" id="RadioGroupLang_6" />
                cancer</label>        
          <label>
                <input class="radio" type="radio" name="RadioGroupLang" value="cardio" id="RadioGroupLang_7" />
                cardio</label>  
        <label>
                <input class="radio" type="radio" name="RadioGroupLang" value="test" id="RadioGroupLang_7" />
                test</label>                        
          </fieldset>
            </span>
          
            <span class="groupmode">
             <fieldset class="smalltext alignleft">
             <legend >mode : </legend>
              <label>
                <input type="radio" name="RadioGroupMode" value="1" id="RadioGroupMode_0"  checked/>
                Contexts</label>
              	<label>
                <input type="radio" name="RadioGroupMode" value="2" id="RadioGroupMode_1" />
                Evaluate</label>
         		<label>
                <input type="radio" name="RadioGroupMode" value="3" id="RadioGroupMode_2"/>
                Procedural</label>        
             </fieldset>   
            </span>
             -->
            </fieldset>
            </form>
            
    </div>
   
    <div id="page_results_articles"> </div>
   
    <div id="page_results_sugestion_menu">
    <span id="close_page_results_sugestion_menu"><img src="images/closered.gif" width="12" height="12" alt="close" /></span>
    
    <form action="" method="get" name="form_sugestion">
    <span class="groupsugestion">
        <label>
          <input type="radio" name="RadioGroupSugestion" value="categories" id="RadioGroupSugestion_0" />
          Categories</label>
      
        <label>
          <input type="radio" name="RadioGroupSugestion" value="semanticspace" id="RadioGroupSugestion_1" checked />
          SemanticSpace</label>

        <label>
          <input type="radio" name="RadioGroupSugestion" value="searchshape" id="RadioGroupSugestion_2" />
          proximity</label>  
    
    </span>
    <span class="groupcontextualize">
        <label>
          <input type="checkbox" name="CheckboxSugestion" value="true" id="CheckboxSugestion" />
          contextualize</label>                      
    </span>
    </form>
    
    
        <div id="page_results_sugestion"></div>
        
    </div>
        
        <div id="preview"></div>
        <div id="preview_abstract"></div>
         <div id="preview_google"></div>
        <div id="bullemarvin"><span id="textbullemarvin">Suggestion</span><div id="imagebulle"></div></div>
        
        <div id="marvin"><img id="imarvin" src="images/marvinapellec.png"  /></div>

</div>
</body>
</html>
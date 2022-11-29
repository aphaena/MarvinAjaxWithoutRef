// JavaScript Document
var mysearch;
var pagestart=1;
var lang='fr';
var mode='1';
var modepreview='1';
var myInterval=0;
var myIntervalBulle=0;
var myIntervalarticles=0;
var myIntervalsimilar=0;
var searchtmp;
var sugestion=0;
var contextualize=0;
var menu_option=false;
var menu_suggestion=false;
var entercontextfirst = 1;
var memsearch;
var memrowid;
var google_display = 0;
var titlewiki = "";
		
$(function(){
	
	
$("#text_search").focus();

//display_suggestion();	// init gros bug à corriger
$("#page_results_sugestion_menu").hide();
$("#page_results_sugestion").hide();

$("#preview").hide();
//$("#preview_abstract").hide();

$("#form_search_option").hide();

$("#bullemarvin").hide();
$("#options").hide();
$("#preview_google").hide();

//move_page_results_articles("");
function move_page_results_articles(choice) 
{
	if(choice == "form_search_main")
	{
		$("#page_results_articles").offset({ top: 64, left: $("#form_search_main").offset().left });
	}
	if(choice == "form_search_option")
	{
		$("#page_results_articles").offset({ top: $("#form_search_option").offset().top+$("#form_search_option").height(), left: $("#form_search_main").offset().left });
	}
	if(choice == "form_suggestion")
	{		
		$("#page_results_articles").offset({ top: $("#page_results_sugestion_menu").offset().top+$("#page_results_sugestion_menu").height()+10, left: $("#form_search_main").offset().left });
		//alert($("#page_results_sugestion_menu").height());
	}
	if (choice == "")
	{
		if(menu_option==true) {move_page_results_articles("form_search_option");	}
		if (menu_suggestion==true){  move_page_results_articles("form_suggestion");	}
		if((menu_suggestion==false) && (menu_option==false) ) {	move_page_results_articles("form_search_main");	}
		//alert (menu_option+"\n "+menu_suggestion);
	}
}

$("#button_mixword").click(function(e){
		
		var str = $("#text_search").val();
		var strmixed="";
		//$table = explode(" ",$str);	
		var table = str.split(" ");
	
		shuffle(table);
	
		for ( var i=0; i< table.length; i++ )
		{			
			strmixed = strmixed+table[i]+" ";
		}		
			
		$("#text_search").val(trim(strmixed ));
		send_form();
});

$("#display_option").click(function(e){
		if( $("#options").is(":visible")) { $("#options").hide(500); }
		else { $("#options").show(500);}
});


shuffle = function(o){ //v1.0
	for(var j, x, i = o.length; i; j = parseInt(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
	return o;
};

function trim (myString) 
{ 
 return myString.replace(/^\s+/g,'').replace(/\s+$/g,'') 
} 



$("#but_sugestion").click(function(e)
	{		
		$("#preview").hide(500);
		display_suggestion();	
	});

function display_suggestion()
{
		$("#preview_abstract").hide();
		$("#cadre_text_abtract").hide();

		$("#page_results_sugestion_menu").show();
		$("#page_results_sugestion").show();
		//menu_height =  $("#page_results_sugestion_menu").height();


		//$("#page_results_articles").offset({ top:window.pageXOffset+64 , left: $("#page_results_sugestion_menu").offset().left });
		
		//$("#preview").offset({ top: $("#page_results_sugestion_menu").offset().top, left: $("#page_results_sugestion_menu").offset().left+$("#page_results_sugestion_menu").width() });
		memsearch = $("#text_search").val();
		initsugestion();		
		suggestions(memsearch);

		if(menu_option==true) 
		{
			$("#page_results_sugestion_menu").offset({ top:  $("#form_search_option").offset().top +  $("#form_search_option").height()+2, left: $("#form_search_main").offset().left });
		}
		else
		{		
			menu_option=false;
			$("#page_results_sugestion_menu").show();
			$("#page_results_sugestion_menu").offset({ top: 64, left: $("#form_search_main").offset().left });	
			//alert( window.pageYOffset+64);
				
		}
		menu_suggestion=true;
		 move_page_results_articles("");	
}


$("#but_options").click(function(e)
{
	$("#preview").hide(500);
	$("#form_search_option").show();
	$("#page_results_sugestion_menu").offset({ top: $("#form_search_option").offset().top+$("#form_search_option").height(), left: $("#form_search_option").offset().left });	
	menu_option=true;
	
	if(menu_suggestion==true) move_page_results_articles("form_suggestion");	
	else move_page_results_articles("form_search_option");	
});
	
$("#close_page_results_sugestion_menu").click(function(e)
{
	$("#preview").hide();		
	
	$("#page_results_sugestion_menu").hide();
	menu_suggestion=false;
	if(menu_option==true)  move_page_results_articles("form_search_option");	
	else move_page_results_articles("form_search_main");	
});

$("#close_options_menu ").click(function(e)
{
	$("#form_search_option").hide();
	$("#page_results_sugestion_menu").offset({ top: $("#form_search_main").offset().top+$("#form_search_main").height()+32, left: $("#form_search_main").offset().left });
	menu_option=false;
	if( menu_suggestion==false) move_page_results_articles("form_search_main");	
	else move_page_results_articles("form_suggestion");	
});
	
		
		
	
	
	$("#button_ok").click(function(e)
	{		
	send_form();
		/*
		if(menu_option==true) move_page_results_articles("form_search_option");	
		if (menu_suggestion==true) move_page_results_articles("form_suggestion");	
		if(menu_suggestion==false && menu_option==false )	move_page_results_articles("form_search_main");	
		*/
		//$("#page_results_articles").offset({ top:window.pageXOffset+64 , left: $("#page_results_sugestion_menu").offset().left });
	});
	
	
	function send_form()
	{
			strsearch = $('#text_search').val();
			
			if(strsearch.indexOf( "-", 0 ) != -1 )
			{ 
				strsearch = strsearch.replace(/\-/g, " ");
				$('#text_search').val(strsearch);	
			}
			
		initmode();
		initlang();
		if(menu_suggestion ==true) display_suggestion();	
		ajaxquery();			
		move_page_results_articles("");	
		similar_articles();		
		if(marvin==1)
		{
			suggestionsbulle($("#text_search").val());
		}
	}

	send_google();
	function send_google()
	{
		
		$('#button_compare').click(function() {
			//alert(lang);
		if(lang=='eng') langtmp='en';
		if(lang=='fr') langtmp='fr';
		var href = 'http://www.google.com/search?q=site:'+langtmp+'.wikipedia.org '+$("#text_search").val();
		if (href.indexOf('http://') != -1 || href.indexOf('https://') != -1) {
			var host = href.substr(href.indexOf(':')+3);
			if (host.indexOf('/') != -1) {
				host = host.substring(0, host.indexOf('/'));
			}
			if (host != window.location.host) {
				window.open(href);
				return false;
			}
		}
	
	
		});

		$('#button_compare').mouseenter(function(event) {
				google_display	= 1;
				compare_google();
		});

	

	}
		$('#text_search').click(function(event) { 				
			$("#preview").hide(500);
		});
		
		$('#page_results_articles').mouseenter(function(event) { 						
			$(".sf_suggestion").hide(1000);
		});
		
		$('#form_search_main').mouseenter(function(event) {
									
			$(".sf_suggestion").show();
		});
		

		/*
        $(this).bind("keyup", function(event){
			
			strsearch = $('#text_search').val();
			
			if(strsearch.indexOf( "-", 0 ) != -1 )
			{ 
				strsearch = strsearch.replace(/\-/g, " ");
				$('#text_search').val(strsearch);	
			}
			
			myIntervalarticles = clearTimeout(myIntervalarticles); 
			myIntervalarticles = 0;
			myIntervalarticles = window.setTimeout(send_form ,2000);
	 	});
		*/
      $(this).bind("input paste", function(event){
			
			strsearch = $('#text_search').val();
			
			if(strsearch.indexOf( "-", 0 ) != -1 )
			{ 
				strsearch = strsearch.replace(/\-/g, " ");
				$('#text_search').val(strsearch);	
			}
			
			myIntervalarticles = clearTimeout(myIntervalarticles); 
			//myIntervalarticles = 0;
			myIntervalarticles = window.setTimeout(send_form ,1000);
	 	});

	
		$('#text_search').keypress(function(event) { 				

			var keycode = (event.keyCode) ? (event.keyCode) : (event.which);
			
			myIntervalarticles = 0;
			myIntervalarticles = clearTimeout(myIntervalarticles); 
			//$("#preview").hide(500);
			if(keycode == '13') 
			 {

			 	initmode();	
				$("#preview").hide(500);
				mysearch = $("#text_search").val(); 
				if(menu_suggestion ==true) display_suggestion();		
				ajaxquery();
				move_page_results_articles("");
				if(marvin==1) suggestionsbulle($("#text_search").val());
				similar_articles();	
			
				$('#text_search').focus();									
				event.preventDefault();	
			
					
		  	}
			if(keycode == '32') 
			{
				send_form();
			}
					 		  
		});
			
	var marvin=0;
	$("#checkboxMarvin").click(function()
	{
		 if( $(this).is(':checked') ) 
		 {
			  marvin=1;
			  $("#bullemarvin").show(1000);
			  $("#marvin").show(1000); 
			  send_form();
		 }
		 else {
			  marvin=0; 
		 	  send_form(); clearTimeout(myIntervalBulle); 
			  $("#bullemarvin").hide();
			  $("#marvin").hide(1000);
			  }
	});

	var mimages=0;
	$("#checkboxImages").click(function()
	{
		 if( $(this).is(':checked') )
		 {			
		    mimages=1;		
			send_form();			
		 }
		 else
		 {			   
		 	mimages=0; 
			clearTimeout(myInterval);
		    $("#preview").hide();
		 }
	});

	$(".grouplang input[type=radio]").click(function(e)
	{		
		//alert($(this).val());
		lang=$(this).val();
		ajaxquery() ;
	});		
	

	$(".groupmode input[type=radio]").click(function(e)
	{		
		//alert($(this).val());
		mode=$(this).val();
		ajaxquery() ;
		
	});
	
	$(".groupsugestion input[type=radio]").click(function(e)
	{		
		sugestion=$(this).val();
		suggestions($("#text_search").val()) ;
		
	});
	
	$(".groupcontextualize input[type=checkbox]").click(function(e)
	{		
		//contextualize=$(this).val();
		contextualize= $('input[type=checkbox][name=CheckboxSugestion]:checked').attr('value');	
		suggestions($("#text_search").val()) ;
		
	});

	
	function initmode()
	{
		//mode=$(".groupmode input[type=radio][@checked]").val();		
		mode=$('input[type=radio][name=RadioGroupMode]:checked').attr('value');		
	}
	
	function initlang()
	{
		//lang=$('input[type=radio][name=RadioGroupLang]:checked').attr('value');
		lang=$('input[type=radio][name=RadioGroupOpt]:checked').attr('value'); // doubble gestion selection
		//lang=$(".grouplang input[type=radio][@checked]").val();
		
	}
		
	function initsugestion()
	{
		sugestion= $('input[type=radio][name=RadioGroupSugestion]:checked').attr('value');	
		initcontextualize();	
	}
	
	function initcontextualize()
	{				
		contextualize= $('input[type=checkbox][name=CheckboxSugestion]:checked').attr('value');				
	}
	
	
	function ajaxquery() 
	{
		initlang();
		initmode();	  
		pagestart=1;
		//$("#preview").hide();
		mysearch = $("#text_search").val(); 
		//alert(mysearch);
		articles(encodeURIComponent(mysearch));				
		
			buttonpages();
			titlereq();	
			link_article();
			similar_articles();
			if (google_display	== 1) { compare_google();}		
			 $("#preview_google").hide(1000); 			
			//alert("ajaxjquery");
				
	}


	function link_article() 
	{					
		$(".link_article").mouseenter(function(el) 
		{				
			myIntervalsimilar = clearTimeout(myIntervalsimilar); 
			titlewiki=$(this).attr("value");	
			//alert(titlewiki);
			myIntervalsimilar = window.setTimeout(load_wiki ,300);												
		});
		
		$(".link_article").mouseleave(function(e)
		{
			myIntervalsimilar = clearTimeout(myIntervalsimilar); 
					
		
		});
		
	
	}

	function similar_articles()
	{					
		$(".similar_articles").mouseenter(function(e)
		{
			$("#preview_google").hide(1000); 	
			myIntervalsimilar = clearTimeout(myIntervalsimilar); 
			rowid=$(this).attr("value");
  			memrowid = rowid;								
			myIntervalsimilar = window.setTimeout(similar_preview ,100);									
		});		
		
		$(".similar_articles").mouseleave(function(e)
		{
			myIntervalsimilar = clearTimeout(myIntervalsimilar); 
			$("#preview_abstract").hide(450);			
		
		});
		
		$(".similar_articles").click(function(e)
		{
			pagestart=1;
			rowid=$(this).attr("value");
			mysearch=rowid;
			
			mode="similar";
				 $.ajax({ type: "GET", url: "articles.php",
				  data: "q="+rowid+"&pagestart="+pagestart+"&lang="+lang+"&mode="+mode,					  
				  success:  function(html){					  			  
					  $("#page_results_articles").html(html); 					  
			  		  similar_articles();		
					  buttonpages();	
					  text_abstract();	
					  titlereq();	  			
					  similar_articles();	
					  link_article();	
					  memrowid = rowid;			
					  					
				  },
				  async: true });	
		});		
						
	}

	function similar_preview() {
			
			pagestartpreview=1;
			//rowidpreview=$(this).attr("value");
			rowidpreview = memrowid;
			//alert("similar " + rowidpreview + lang);
			//mysearch=rowidpreview;
			modepreview="similar";
				 $.ajax({ type: "GET", url: "articlespreview.php",
				  data: "qpreview="+rowidpreview+"&pagestartpreview="+pagestartpreview+"&lang="+lang+"&modepreview="+modepreview,					  
				  success:  function(html){	
				  
				  	$("#preview_abstract").show();				  			  
					$("#preview_abstract").html(html);
					$("#preview_abstract").offset({ top: 45+$(window).scrollTop() });
					//alert($(window).scrollTop());
					//$("#preview_abstract").click(function(){ $("#preview_abstract").hide(1000); });	 
	 				similar_articles();	
				
					//send_form();					  		  									
				  },
				  async: true });
	}



	function compare_google() {

	if(lang=='eng') {langtmp='en'; langext = 'com';}
	if(lang=='fr') { langtmp='fr'; langext = 'fr';}
		
		 $.ajax({ url: 'compare_search.php',
				  data: "url=http://www.google."+langext+"/search?q="+trim($('#text_search').val())+" site:"+langtmp+".wikipedia.org",						  
					  
				  success:  function(msg){					  
				  //alert(msg);
						$("#preview_google").show();				  			  
						$("#preview_google").html(msg);						
						//$("#preview_google").offset({ top: $("#form_search_main").top(-15) , left: $("#bodycadre").width()-700 });
						$("#preview_google").click(function(){ google_display = 0; $("#preview_google").hide(1000); });
						$("#preview_google").offset({ top: 60+$(window).scrollTop()  });
				  },
				  async: true });			
	}


	function load_wiki() {
	if(lang=='eng') {langtmp='en'; langext = 'com';}
	if(lang=='fr') { langtmp='fr'; langext = 'fr';}
		//http://fr.wikipedia.org/w/index.php?title=Coeur_de_diamant&redirect=no
		 $.ajax({ url: 'proxyurl.php',
				  type: "GET",					  
				  data: { url: "http://"+langtmp+".wikipedia.org/wiki/" , title: titlewiki, redirect: "no"},  
				  //data:  "url=http://"+langtmp+".wikipedia.org/wiki/"+titlewiki,  
				  success:  function(msg){					  
				  //alert(msg);
		
						$("#preview_google").show();				  			  
						$("#preview_google").html(msg);		
						$("#preview_google").offset({ top: 60+$(window).scrollTop()  });
						$("#closepreview").bind('click', function(){ google_display = 0; $("#preview_google").hide(1000); });									
						//$("#preview_google").offset({ top: $("#form_search_main").top(-15) , left: $("#bodycadre").width()-700 });
						$("#preview_google").bind('click', function(){ google_display = 0; $("#preview_google").hide(1000); });						
				  },
				  async: true });
	}


	function suggestions(thistext)
	{
			//alert(thistext);			
		   $.ajax({ type: "GET", url: "suggestions.php",
				  data: "q="+encodeURIComponent(thistext)+"&lang="+lang+"&sugestion="+sugestion+"&contextualize="+contextualize,						  
				  success:  function(msg){					  
					$("#page_results_sugestion").html(msg); 	
					move_page_results_articles("");	
					if(mimages==1) preview_sugestion();
					move_page_results_articles("");	
					hightlightbuttons();
					hightlight();
					cadrecontext();			  			  	
					$(".cadrecontextcolor1").scroll();
					$(".cadrecontextcolor2").scroll();	
					$("#page_results_sugestion_menu").show();				  											
				  },
				  async: true });							  	  
	}
	
	var tabsuggestionbulle = [];
	function suggestionsbulle(thistext)
	{		
		   $.ajax({ type: "GET", url: "suggestionsbulle.php",
				  data: "q="+encodeURIComponent(thistext)+"&lang="+lang+"&sugestion=semanticspace",						  
				  success:  function(msg){					  
					/*$("#page_results_sugestion").html(msg); 	*/
					tabsuggestionbulle = eval('(' + msg + ')');

					random_bulle();
					
				  },
				  async: false });							  	  
	}

var cptbulle=0;
	function random_bulle()
	{		
	/*tabsuggestionbulle[cptbulle]*/
	//alert("ok");
	clearInterval(myIntervalBulle);
		cptbulle=Math.floor(Math.random()*tabsuggestionbulle.length);		
		//alert(cptbulle);
		if(tabsuggestionbulle[cptbulle]== null) { random_bulle();}
		locutionclean = tabsuggestionbulle[cptbulle].replace(/_/g," ");
		locutionclean = locutionclean.replace(/#/g," ");
		random_image_bulle(locutionclean);
		
		$("#textbullemarvin").html("<div class='locutionbulle'> "+locutionclean+"</div>");
		myIntervalBulle = window.setTimeout(random_bulle ,5000);
	}
	


	function cadrecontext()
	{
			$(".cadrecontext").click(function(e) {				
				strsearch = $(this).children().attr("title");				
				$("#text_search").val(strsearch); 
				entercontextfirst = 1;
				send_form();		
			});
			
			$(".cadrecontext").hover(function(e) {	
				memsearch = $("#text_search").val(); 
				strsearch = $(this).children().attr("title");	
				if( entercontextfirst == 1 ) { entercontextfirst =0;	}
				else {  $("#text_search").val(strsearch); 	}
			//$("#preview").show();
			//$("#preview").offset({ top: $("#page_results_sugestion_menu").offset().top, left: $("#page_results_sugestion_menu").offset().left+$("#page_results_sugestion_menu").width()+5 });
			//$("#preview").html(strsearch); 
			},function(){
				$("#text_search").val(memsearch); 				
		 	 //$("#preview").hide();
		   clearInterval(myInterval);
			});
			

			
	}
	
	
	
	// traitement chaine incompatible
	function linked_with()
	{
		$("#linked_with").click(function(e) {
			strsearch = $(this).text()
			strsearch = strsearch.replace(/\./g,"");		
			strsearch = strsearch.replace(/  /g,"");				
			$("#text_search").val(strsearch); 
		});
	}	

	function articles(thistext)
	{
		 $.ajax({ type: "GET", url: "articles.php",
				  data: "q="+thistext+"&pagestart="+pagestart+"&lang="+lang+"&mode="+mode,					  
				  success:  function(msg){					  
					  $("#page_results_articles").html(msg); 					  											
					  text_abstract();	
					  buttonpages();
					  titlereq();
  					  similar_articles();
					  link_article();
					   //hightlighttexte('#cadre_text_abtract');			
				  },
				  async: true });					  
	}

	//$('#text_search').bind('keydown', function(event) { 				



	function hightlight()
	{
		$(".hightlight").hover(function(){				
		   $(this).addClass("highlight");
			
		 },function(){
		   $(this).removeClass("highlight");
		   clearInterval(myInterval);
		 });
	}


	hightlightbuttons();
	function hightlightbuttons()
	{	
		$(".button").hover(function(){				
		   $(this).addClass("highlight");
			if(mimages==1) $("#preview").show();
			if(mimages==1) $("#preview").offset({ top: $("#page_results_sugestion_menu").offset().top, left: $("#page_results_sugestion_menu").offset().left+$("#page_results_sugestion_menu").width()+5 });
		 },function(){
		   $(this).removeClass("highlight");
		   clearInterval(myInterval);
		 });

		$(".button").click(function(){						 
			  mysearch = $("#text_search").val();
			  button_text = $(this).text();
			  /*if( button_text.indexof(mysearch)!=-1)
			  {  $("#text_search").val($(this).text());}
			  else { $("#text_search").val(mysearch +" "+ $(this).text()); }			 */
			  $("#text_search").val(mysearch +" "+ $(this).text());
			  $("#text_search").focus();
			  memsearch = $("#text_search").val();
			initsugestion();
			suggestions(memsearch);
			  
		});
	}
	
	function titlereq(){
		$(".title").click(function(){						 
			  //mysearch = $("#text_search").val();
			  //button_text = $(this).text();
			  /*if( button_text.indexof(mysearch)!=-1)
			  {  $("#text_search").val($(this).text());}
			  else { $("#text_search").val(mysearch +" "+ $(this).text()); }			 */
			  $("#text_search").val( $(this).text());
			  $("#text_search").focus();
			  send_form();
			  
		});
	}
	
	
	function text_abstract()
	{
		$('.text_abstract').click(function(){	
			rowid = $(this).attr('value');
			mysearch = $("#text_search").val();
			$.ajax({ type: "GET", url: "text_abtract.php",
				  data: "q="+encodeURIComponent(mysearch)+"&lang="+lang+"&rowid="+rowid,					  
				  success:  function(msg){					  
					  $("#preview_abstract").html(msg); 						    
					    $("#preview_abstract").show(500);		
	// $("#preview_abstract").offset({ top: window.pageYOffset , left: $("#form_search_main").offset().right-5 });// $("#form_search_main").offset().top+$("#form_search_main").height()+17
	// $("#preview_abstract").offset({ top: 45+$(window).scrollTop()  , left: $("#form_search_main").offset().right-5 });// $("#form_search_main").offset().top+$("#form_search_main").height()+17
$("#preview_abstract").offset({ top: 45+$(window).scrollTop() });// 
	 $("#preview_abstract").click(function(){ $("#preview_abstract").hide(1000); });
	 $('#cadre_text_abtract').click(function(){	
	  //hightlighttexte('#cadre_text_abtract');
	 });
	  //$("#cadre_text_abtract").highlight(mysearch, true, "hls");			  											
				  },
				  async: true });	
			
		});	
		
	}
	
	function preview_sugestion()
	{			
		
		$(".button").bind('mouseenter', function(event) 	{ 		
				clearInterval(myInterval);
			memsearch = $(this).text();										
			myInterval = window.setTimeout(ajaxgoogle ,500);										
			
			$('#preview').bind('mouseenter', function(event) 	{ 		
			 	clearInterval(myInterval);
				linked_with();		
			});
			
		});
		
	
	}
	
	
	
	function closepreview() {
		$('#buttonclosepreview').click(function(event)	{
			$("#preview").hide(500);
		});
	}
	
	function test()
	{
			alert("test");	
	}
	
	function ajaxgoogle()
	{		
	 $("#preview").show();
		
		//searchtmp=utf8_encode(searchtmp);		
		tmp = $.ajax({ type: "GET", url: "imageproxyajaxForForm.php",
				  data: "q="+encodeURIComponent(memsearch)+"&qo="+encodeURIComponent($("#text_search").val())+"&lang="+lang,					  
				  success:  function(html){					  
				  	$("#preview").html(html); 	
					closepreview();		
				  	/*$("#page_results_sugestion").bing('mouseleave', function(event) 	{ 	});						 	
					linked_with();  
					*/							 											
				  },
				  async: true });	
				  clearInterval(myInterval);				

	}

var imgs=new Array();
imgs[0]="images/marvinapellec.png";
imgs[1]="images/marvinbaguettec.png";
imgs[2]="images/marvinbook2c.png";
imgs[3]="images/marvinbookc.png";
imgs[4]="images/marvincontext2c.png";
imgs[5]="images/marvincontextc.png";
imgs[6]="images/MarvinFaxc.png";
imgs[7]="images/marviniphonec.png";
imgs[8]="images/marvinmeteoc.png";
imgs[9]="images/marvinmusclorc.png";
imgs[10]="images/marvinparentalc.png";
imgs[11]="images/marvinpointc.png";

var cpt=Math.floor(Math.random()*12);
changeimages();
function changeimages()
{
	document.getElementById("imarvin").src=imgs[cpt];
	cpt=Math.floor(Math.random()*12);
	if(cpt>=imgs.length) cpt=0;
	window.setTimeout(changeimages ,30000);
}

	
	
	
	function buttonpages()
	{
		$('.buttonpages').click(function(e)
		{					
		clearInterval(myInterval);
			pagestart = $(this).attr('value');
			articles(encodeURIComponent(mysearch));		
			similar_articles();	
			buttonpages();	
			$("body").animate({scrollTop:0}, 'slow');			
			$(window).scrollTop(0);
			move_page_results_articles();
		});
	}
	
	/****************************** New Google API but restricted ******************************************/
	
	
  function searchComplete(searcher) {
	  /*alert(searcher.results.length);*/
  // Check that we got results  
  if (searcher.results && searcher.results.length > 0) {
    // Grab our content div, clear it.
    var contentDiv = document.getElementById('imagebulle');
    contentDiv.innerHTML = '';

    // Loop through our results, printing them to the page.
    var results = searcher.results;
	/*alert(results.length);*/
	rnd = Math.floor(Math.random()*results.length);
	var result = results[rnd];
	var imgContainer = document.createElement('div');
	
	 var newImg = document.createElement('img');
	 newImg.className = 'imagebulle2';
	 newImg.src = result.tbUrl;
	  imgContainer.appendChild(newImg);
	  contentDiv.appendChild(imgContainer);
	/*
    for (var i = 0; i < results.length; i++) {
      // For each result write it's title and image to the screen
      var result = results[i];
      var imgContainer = document.createElement('div');

     var title = document.createElement('h2');
      // We use titleNoFormatting so that no HTML tags are left in the title
      title.innerHTML = result.titleNoFormatting;

      var newImg = document.createElement('img');
      // There is also a result.url property which has the escaped version
      newImg.src = result.tbUrl;

      imgContainer.appendChild(title);
      imgContainer.appendChild(newImg);

      // Put our title + image in the content
      contentDiv.appendChild(imgContainer);
    }
	*/
  }
}

function random_image_bulle(ima) {
  // Our ImageSearch instance.
  var imageSearch = new google.search.ImageSearch();

  // Restrict to extra large images only
  imageSearch.setRestriction(google.search.ImageSearch.RESTRICT_IMAGESIZE,
                             google.search.ImageSearch.IMAGESIZE_MEDIUM);						 
	
  // Here we set a callback so that anytime a search is executed, it will call
  // the searchComplete function and pass it our ImageSearch searcher.
  // When a search completes, our ImageSearch object is automatically
  // populated with the results.
  imageSearch.setSearchCompleteCallback(this, searchComplete, [imageSearch]);

  // Find me a beautiful car.
  imageSearch.execute(mysearch + " " + ima);
}
//google.setOnLoadCallback(OnLoad);​
	
	
/**********************************************************************/	

});	

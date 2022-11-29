// JavaScript Document
var mysearch;
var pagestart=1;
var lang='fr';
var mode='1';
var myInterval=0;
var searchtmp;
var sugestion=0;
		
$(function(){
	
$("#text_search").focus();

//$("#page_results_sugestion").hide();
$("#preview").hide();
$("#page_results_sugestion_menu").hide();
$("#preview_abstract").hide();

$("#but_sugestion").click(function(e)
	{		
		$("#preview_abstract").hide();
		$("#cadre_text_abtract").hide();
		$("#page_results_sugestion_menu").show();
		$("#page_results_sugestion").show();
		//menu_height =  $("#page_results_sugestion_menu").height();
		$("#page_results_sugestion_menu").offset({ top: $("#form_search_option").offset().top+$("#form_search_option").height(), left: $("#form_search_option").offset().left });
		//$("#page_results_sugestion").offset({ top: $("#page_results_sugestion_menu").offset().top + $("#page_results_sugestion_menu").height(), left: $("#page_results_sugestion_menu").offset().left });
		
		//$("#preview").offset({ top: $("#page_results_sugestion_menu").offset().top, left: $("#page_results_sugestion_menu").offset().left+$("#page_results_sugestion_menu").width() });
		searchtmp = $("#text_search").val();
		initsugestion();
		suggestions(searchtmp);
	
	});


	
$("#close_page_results_sugestion_menu").click(function(e)
{
	$("#preview").hide();
	$("#page_results_sugestion_menu").hide();
});
	
	$("#button_ok").click(function(e)
	{		
		initmode();
		initlang();
		ajaxquery();
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
	
	function initmode()
	{
		//mode=$(".groupmode input[type=radio][@checked]").val();		
		mode=$('input[type=radio][name=RadioGroupMode]:checked').attr('value');		
	}
	
	function initlang()
	{
		lang=$('input[type=radio][name=RadioGroupLang]:checked').attr('value');
		//lang=$(".grouplang input[type=radio][@checked]").val();
		
	}
	
	
	function initsugestion()
	{
		sugestion= $('input[type=radio][name=RadioGroupSugestion]:checked').attr('value');		
	}
	
	function ajaxquery() 
	{
		//alert("test");
		$("#preview_abstract").hide();
		initlang();
		initmode();	  
		pagestart=1;
		$("#preview").hide();
		$("#page_results_sugestion_menu").hide();
		mysearch = $("#text_search").val(); 
		//alert($("#text_search").val());	  
		//suggestions(mysearch);	
		articles(mysearch);
		
		similar_articles();
			buttonpages();
				
	}


	function similar_articles()
	{
		$(".similar_articles").click(function(e)
		{
			pagestart=1;
			rowid=$(this).attr("value");
			mysearch=rowid;
			mode="similar";
				 $.ajax({ type: "GET", url: "http://www.marvinbot.com/marvinajax/articles.php",
				  data: "q="+rowid+"&pagestart="+pagestart+"&lang="+lang+"&mode="+mode,					  
				  success:  function(msg){					  
					  $("#page_results_articles").html(msg); 					  
			  		  similar_articles();		
					  buttonpages();	
					  text_abstract();		  											
				  },
				  async: false });	
		});
	}

	function suggestions(thistext)
	{
			//alert(thistext);			
		   $.ajax({ type: "GET", url: "http://www.marvinbot.com/marvinajax/suggestions.php",
				  data: "q="+thistext+"&lang="+lang+"&sugestion="+sugestion,						  
				  success:  function(msg){					  
					  $("#page_results_sugestion").html(msg); 					  											
				  },
				  async: false });	
		$("#page_results_sugestion").show();				  
			preview_sugestion();	
			hightlightbuttons();			  			  			  
	}
	
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
		 $.ajax({ type: "GET", url: "http://www.marvinbot.com/marvinajax/articles.php",
				  data: "q="+thistext+"&pagestart="+pagestart+"&lang="+lang+"&mode="+mode,					  
				  success:  function(msg){					  
					  $("#page_results_articles").html(msg); 					  											
					  text_abstract();
				  },
				  async: false });					  
	}

	$('#text_search').bind('keydown', function(event) { 				
		
			//alert("keydown");
			var keycode = (event.keyCode ? event.keyCode : event.which);
			if(keycode == '13') 
			 {
			 // event.preventDefault();	
			 initmode();	
			 mysearch = $("#text_search").val(); 
			 //mysearch = mysearch.replace(/'/g, '');		
			 //alert("enter");
			ajaxquery();
			$('#text_search').focus();	
									
			event.preventDefault();								
		  }			
		});



	hightlightbuttons();
	function hightlightbuttons()
	{	
		$(".button").hover(function(){				
		   $(this).addClass("highlight");
			$("#preview").show();
			$("#preview").offset({ top: $("#page_results_sugestion_menu").offset().top, left: $("#page_results_sugestion_menu").offset().left+$("#page_results_sugestion_menu").width() });
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
			  
		});
	}
	
	function text_abstract()
	{
		$('.text_abstract').click(function(){	
			rowid = $(this).attr('value');
			mysearch = mysearch = $("#text_search").val();
			$.ajax({ type: "GET", url: "http://www.marvinbot.com/marvinajax/text_abtract.php",
				  data: "lang="+lang+"&rowid="+rowid,					  
				  success:  function(msg){					  
					  $("#preview_abstract").html(msg); 	
					    $("#preview_abstract").show(500);		
	 $("#preview_abstract").offset({ top: $("#page_results_sugestion_menu").offset().top+10, left: $("#page_results_sugestion_menu").offset().left+$("#page_results_sugestion_menu").width()+10 });
	  hightlighttexte('#cadre_text_abtract');
	  //$("#cadre_text_abtract").highlight(mysearch, true, "hls");			  											
				  },
				  async: false });	
			
		});	
	}
	
	function preview_sugestion()
	{			
		
		$(".button").bind('mouseenter', function(event) 	{ 		
				clearInterval(myInterval);
			searchtmp = $(this).text();				
			//alert(searchtmp);					
			myInterval = window.setTimeout(ajaxgoogle ,200);										
			
			$('#preview').bind('mouseenter', function(event) 	{ 		
			 	clearInterval(myInterval);
				linked_with();		
			});
			
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
		$.ajax({ type: "GET", url: "http://www.marvinbot.com/marvinajax/imageproxyajaxForForm.php",
				  data: "q="+searchtmp+"&qo="+$("#text_search").val()+"&lang="+lang,					  
				  success:  function(msg){					  
					  $("#preview").html(msg); 					  											
				  },
				  async: false });	
			$("#page_results_sugestion").bing('mouseleave', function(event) 	{ 
				clearInterval(myInterval);				
		});		
		linked_with();  
	}
	
	
	
	function buttonpages()
	{
		$('.buttonpages').click(function(e)
		{					
		clearInterval(myInterval);
			pagestart = $(this).attr('value');
			//mysearch = $("#text_search").val(); 	
			//alert(mysearch);	
			articles(mysearch);		
			similar_articles();	
			buttonpages();	
		});
		}


	  
			 
			  function hightlighttexte(idorclass){
				  
				  myString = mysearch;
				  $(idorclass).highlight(myString);
				  myString= myString.replace(" de "," ");
				  myString= myString.replace(" à "," ");
				  myString= myString.replace(" l "," ");
				  myString= myString.replace(" d "," ");
				  myString= myString.replace(" la "," ");
				  myString= myString.replace(" La "," ");
				  myString= myString.replace(" le "," ");
				  myString= myString.replace(" Le "," ");
				  myString= myString.replace(" a "," ");
				  myString= myString.replace(" des "," ");
				  myString= myString.replace(" ce "," ");
				  myString= myString.replace(" Ce "," ");
				  myString= myString.replace(" cet "," ");
				  myString= myString.replace(" Cet "," ");
				  myString= myString.replace(" avec "," ");
				  myString= myString.replace(" qui "," ");
				  myString= myString.replace(" que "," ");
				  myString= myString.replace(" ou "," ");
				  myString= myString.replace(" une "," ");
				  myString= myString.replace(" Une "," ");
				  myString= myString.replace(" in "," ");
				  myString= myString.replace(" In "," ");
				  myString= myString.replace(" of "," ");
				  myString= myString.replace(" Of "," ");
				  myString= myString.replace(" the "," ");
				  myString= myString.replace(" The "," ");
				  myString= myString.replace(" an "," ");
				  myString= myString.replace(" An "," ");
				  myString= myString.replace(" Or "," ");
				  myString= myString.replace(" as "," ");
				  myString= myString.replace(" As "," ");
				 // alert(myString);
				  
				  myArray = myString.split(" "); 
				  for(i=0;i<myArray.length;i++) 
				  { 
				   $(idorclass).highlight(myArray[i], true, "hls");	
				  }
				  				  				 			  
			  }



 jQuery.fn.extend({
    highlight: function(search, insensitive, hls_class){
      var regex = new RegExp("(<[^>]*>)|(\\b"+ search.replace(/([-.*+?^${}()|[\]\/\\])/g,"\\$1") +")", insensitive ? "ig" : "g");
      return this.html(this.html().replace(regex, function(a, b, c){
        return (a.charAt(0) == "<") ? a : "<strong class=\""+ hls_class +"\">" + c + "</strong>";
      }));
    }
  });
  jQuery(document).ready(function($){
    if(typeof(hls_query) != 'undefined'){
      $("#post-area").highlight(hls_query, 1, "hls");
    }
  });



});		    			

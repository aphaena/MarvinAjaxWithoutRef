/* 

	SearchField 
	written by Alen Grakalic, provided by Css Globe (cssglobe.com)
	please visit http://cssglobe.com/post/1202/style-your-websites-search-field-with-jscss/ for more info
	
	
	
	
	
*/

this.searchfield = function(){
	
	// CONFIG 
	
	// this is id of the search field you want to add this script to. 
	// You can use your own id just make sure that it matches the search field in your html file.
	var id = "text_search";
	
	// Text you want to set as a default value of your search field.
	var defaultText = "";	
	
	// set to either true or false
	// when set to true it will generate search suggestions list for search field based on content of variable below
	var suggestion = true;
	
	// static list of suggestion options, separated by comma
	// replace with your own
	var suggestionText = ""; 
	
	// inity variable du timer pour la frappe des touches. Clearinterval
	// clearInterval(myInterval); pour reseter.
	var myInterval=0;
	
	// END CONFIG (do not edit below this line, well unless you really, really want to change something :) )
	
	// Peace, 
	// Alen

	var field = document.getElementById(id);	
	var classInactive = "sf_inactive";
	var classActive = "sf_active";
	var classText = "sf_text";
	var classSuggestion = "sf_suggestion";
	var classPreview = "sf_preview";
	var memsearch ="";
	this.safari = ((parseInt(navigator.productSub)>=20020000)&&(navigator.vendor.indexOf("Apple Computer")!=-1));
	//&& !safari
	if(field && !safari){
		field.value = defaultText;
		field.c = field.className;		
		field.className = field.c + " " + classInactive;
		field.onfocus = function(){
			this.className = this.c + " "  + classActive;
			this.value = (this.value == "" || this.value == defaultText) ?  "" : this.value;
		};
		
		field.onblur = function(){
			this.className = (this.value != "" && this.value != defaultText) ? this.c + " " +  classText : this.c + " " +  classInactive;
			this.value = (this.value != "" && this.value != defaultText) ?  this.value : defaultText;
			clearList();
		};
		
		if (suggestion){
			
			var selectedIndex = 0;
						
			field.setAttribute("autocomplete", "off");
			var div = document.createElement("div");
			var list = document.createElement("ul");
			list.style.display = "none";
			div.className = classSuggestion;			
			//list.style.width = field.offsetWidth + "px";
			//list.style.width = "200px";
			div.appendChild(list);
			field.parentNode.appendChild(div);	

			field.onkeypress = function(e){
				clearInterval(myInterval); 
				var key = getKeyCode(e);
		
				if(key == 13){ // enter
					selectList();
					selectedIndex = 0;
					return false;
				};	
			};
				
			field.onkeyup = function(e){
			
				var key = getKeyCode(e);
				$(".sf_suggestion").show();
				switch(key){
				case 13: // retour chariot
					selectedIndex = 0;
					clearList();
					$("#preview").hide();
					return false;
					break;			
				case 27:  // esc
					field.value = "";
					selectedIndex = 0;
					clearList();
					$("#preview").hide();
					break;				
				case 38: // up
					navList("up");	
					if(mimages==1) navListSearchImage();									
					break;
				case 40: // down
					navList("down");	
					if(mimages==1) navListSearchImage();	
					break;
				default:
					memsearch = field.value;
					
					clearInterval(myInterval); // reset le timer a chaque frappe
					myInterval = window.setTimeout(ajaxautosuggest ,500);	//	appel de la fonction ajaxautosuggest  après un délai en milliseconde.		

					break;
				};
			};
			
			
			var mimages=0;
			$("#checkboxImages").click(function()
			{
				 if( $(this).is(':checked') )
				 {
					mimages=1;
					
				 }
				 else
				 { mimages=0; clearTimeout(myInterval);}
			});
			
			// appel ajax asynchrone pour l'autosuggestion
			//field.value contient le texte du champs de saisie
			this.ajaxautosuggest = function () {
				  $.ajax({ type: "GET", url: "autosuggestions.php",
				  data: "q="+encodeURIComponent(field.value)+"&lang="+lang+"&sugestion=mixsem",						  
				  success:  function(msg){					  
				  					 
		 			  suggestionText = msg;

	  	 			  startList();						 
				  },
				  async: true });					  				
				  
				};
			
			this.startList = function(){
								
				var arr = getListItems(field.value);
				if(field.value.length > 0){
					createList(arr);
				} else {
					clearList();
				};	
			};
			
			this.getListItems = function(value){
								
				var arr = new Array();
				var src = suggestionText;	
				//alert("1 " + suggestionText);			
				var src = src.replace(/, /g, ",");
				var arrSrc = src.split(",");
				

				//arrSrc.replace('</span>','');
								
				
				var separe_word = value.split(" ");
				var tmpwords = "";
				// baleine ours				
				for(i=0;i<arrSrc.length;i++){
					tmpwords = tmpwords +" "+ arrSrc[i].toLowerCase();
					
					
					for(j=0;j<separe_word.length;j++) {

						arr.push(arrSrc[i]);	
						// détecter le mot 1 le mot 2 etc...						
						/*
						if(arrSrc[i].substring(0,value.length).toLowerCase() == value.toLowerCase())
						{
							arr.push(arrSrc[i]);							
						}
						if(	arrSrc[i].indexOf(separe_word[separe_word.length-1]) > -1 )
						{														
							arr.push(arrSrc[i]);							
						};
						*/
					};
	
				};				
				
					//alert("2 " + tmpwords);
				
				return array_unique(arr);
			};
			
			
			this.array_unique = function (arr) {
			var newArray = [];
			var existingItems = {};
			var prefix = String(Math.random() * 9e9);
			for (var ii = 0; ii < arr.length; ++ii) {
			if (!existingItems[prefix + arr[ii]]) {
			newArray.push(arr[ii]);
			existingItems[prefix + arr[ii]] = true;
			}
			}
			return newArray;
			}
			
			
			this.createList = function(arr){				
				resetList();			
				if(arr.length > 0) {
					for(i=0;i<arr.length;i++){				
						li = document.createElement("li");
						a = document.createElement("a");
						a.href = "javascript:void(0);";
						a.i = i+1;
						a.innerHTML = arr[i];
						li.i = i+1;
						li.onmouseover = function(){
							navListItem(this.i);
							if(mimages==1) navListSearchImage();
							
						};
						a.onmousedown = function(){
							selectedIndex = this.i;
							selectList(this.i);		
							return false;
						};					
						li.appendChild(a);
						list.setAttribute("tabindex", "-1");
						list.appendChild(li);	
					};	
					list.style.display = "block";				
				} else {
					clearList();
				};
			};	
			
			this.resetList = function(){
				var li = list.getElementsByTagName("li");
				var len = li.length;
				for(var i=0;i<len;i++){
					list.removeChild(li[0]);
				};
			};
			
			this.navList = function(dir){	
				clearInterval(myInterval);				
				selectedIndex += (dir == "down") ? 1 : -1;
				li = list.getElementsByTagName("li");
				if (selectedIndex < 1) selectedIndex =  li.length;
				if (selectedIndex > li.length) selectedIndex =  1;
				navListItem(selectedIndex);
			};
			
			this.navListItem = function(index){	
				clearInterval(myInterval);		
				selectedIndex = index;
				li = list.getElementsByTagName("li");
				for(var i=0;i<li.length;i++){
					li[i].className = (i==(selectedIndex-1)) ? "selected" : "";
				};
			};
			
			this.navListSearchImage = function() {
				li = list.getElementsByTagName("li");	
				a = li[selectedIndex-1].getElementsByTagName("a")[0];
				//a.innerHTML = cleanspan( a.innerHTML );
				navtest = navigator.appName;
				//alert(navtest);
				if (navtest == 'Microsoft Internet Explorer') {
					field.value = delete_last_word(memsearch)+" "+a.innerText;	
				}
				else {
					field.value = delete_last_word(memsearch)+" "+a.textContent;
				}
				wordsfilter();	
				clearInterval(myInterval);				
				myInterval = window.setTimeout(ajaxgoogle ,500);
			};
			

			this.selectList = function(){
				$(".sf_suggestion").show();
				li = list.getElementsByTagName("li");	
				a = li[selectedIndex-1].getElementsByTagName("a")[0];		
				//alert(memsearch);
				navtest = navigator.appName;
				//alert(navtest);

				if (navtest == 'Microsoft Internet Explorer') {
					field.value = delete_last_word(memsearch)+" "+a.innerText;	
				}
				else {
					//alert(a.textContent);
					field.value = delete_last_word(memsearch)+" "+a.textContent;
				}
				
			
				
				
				wordsfilter();			
				clearList();

			};		

		

		function cleanspan( str ) {
				str = str.replace('<span class="green">','');
				str = str.replace('<span class="orange">','');
				str = str.replace('<span class="red">','');
				str = str.replace('<span class="blue">','');
				str = str.replace('<span class="grey">','');
				return str;
		}
 
			function delete_last_word( strmemsearch )
			{
				//alert( strmemsearch );
				var newstr='';
				var words = new Array();
				words = strmemsearch.split(' ');	
				//alert(strmemsearch+" "+words.length);
				for(var i=0;i<words.length -1 ;i++)
				{
					newstr = newstr + " " + words[i];
				}
				
				newstr = newstr.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
				
				return newstr;
			}
			
			
			function wordsfilter() {
				  $.ajax({ type: "GET", url: "wordsfilter.php",
				  data: "q="+encodeURIComponent(field.value)+"&lang="+lang+"&sugestion=mixsem",						  
				  success:  function(msg){					  
	  	 			field.value = msg;  	
								 
				  },
				  async: true });	
				  
			}
			
			function ajaxgoogle(){		
			
			//searchtmp=utf8_encode(searchtmp);		

			tmp = $.ajax({ type: "GET", url: "imageproxyajaxForForm.php",
					  data: "q="+encodeURIComponent(memsearch)+"&qo="+encodeURIComponent($("#text_search").val())+"&lang="+lang,					  
					  success:  function(html){					  
						$("#preview").html(html); 	
						$("#preview").show();
						$("#preview").offset({ top: 50, left: 300 });
						closepreview();	
						/*$("#page_results_sugestion").bing('mouseleave', function(event) 	{ 	});							
						linked_with();  				  											
						*/
					  },
					  async: true });	
					  
					  clearInterval(myInterval);						  				
			}	
			
			
		function closepreview() {			
		$("#buttonclosepreview").click(function(event)	{
			$("#preview").hide(500);
		});
	}
			
	};
};
	
	this.clearList = function(){
		$("preview").hide();
		if(list){
			list.style.display = "none";
			selectedIndex = 0;
		};
	};		
	this.getKeyCode = function(e){
		var code;
		if (!e) var e = window.event;
		if (e.keyCode) code = e.keyCode;
		return code;
	};
	
};



// script initiates on page load. 

this.addEvent = function(obj,type,fn){
	if(obj.attachEvent){
		obj['e'+type+fn] = fn;
		obj[type+fn] = function(){obj['e'+type+fn](window.event );}
		obj.attachEvent('on'+type, obj[type+fn]);
	} else {
		obj.addEventListener(type,fn,false);
	};
};
addEvent(window,"load",searchfield);


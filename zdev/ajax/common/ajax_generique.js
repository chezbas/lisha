function ajax_call(configuration,reserve_interne){if(typeof(reserve_interne)=="undefined"){var reserve_interne=new Array();reserve_interne['url_image_wait']='connexion.gif'}if(configuration['delai_tentative']<100){configuration['delai_tentative']=100}if(typeof(reserve_interne['appel_auto'])=="undefined"){reserve_interne['xhr_nombre_essai']=0}if(typeof(configuration['div_wait'])!="undefined"){if(reserve_interne['xhr_nombre_essai']==0){if(typeof(configuration['image_div_wait'])=="undefined"){document.getElementById(configuration['div_wait']).innerHTML='<img src="images/'+reserve_interne['url_image_wait']+'"/>'}else{document.getElementById(configuration['div_wait']).innerHTML='<img src="'+configuration['image_div_wait']+'"/>'}}else{if(typeof(configuration['div_wait_nbr_tentative'])!="undefined"){document.getElementById(configuration['div_wait_nbr_tentative']).innerHTML=(reserve_interne['xhr_nombre_essai']+1)+'/'+configuration['max_tentative']}}}if(typeof(reserve_interne['xhr'])=="undefined"){if(window.XMLHttpRequest){reserve_interne['xhr']=new XMLHttpRequest()}else{alert('Votre navigateur ne supporte pas la technologie Ajax');return false}}else{reserve_interne['xhr'].abort()}reserve_interne['xhr'].open("POST",configuration['page'],true);reserve_interne['xhr'].setRequestHeader("Content-Type","application/x-www-form-urlencoded");reserve_interne['xhr'].send(configuration['param']);reserve_interne['xhr_nombre_essai']++;reserve_interne['xhr'].onreadystatechange=function(){if(reserve_interne['xhr'].readyState==4&&reserve_interne['xhr'].status==200){if(typeof(configuration['debug'])=="undefined"){clearTimeout(reserve_interne['xhr_verif_retour_ajax']);if(typeof(configuration['div_wait'])!="undefined"){document.getElementById(configuration['div_wait']).innerHTML=''}if(typeof(configuration['div_wait_nbr_tentative'])!="undefined"){document.getElementById(configuration['div_wait_nbr_tentative']).innerHTML=''}if(typeof(configuration['div_a_modifier'])=="undefined"){if(configuration['type_retour']==false){if(typeof(configuration['fonction_a_executer_reponse'])!="undefined"){var retour=reserve_interne['xhr'].responseText;if(typeof(configuration['param_fonction_a_executer_reponse'])!="undefined"){eval(configuration['fonction_a_executer_reponse']+'('+configuration['param_fonction_a_executer_reponse']+',retour)')}else{eval(configuration['fonction_a_executer_reponse']+'(retour)')}}return false}else{if(typeof(configuration['fonction_a_executer_reponse'])!="undefined"){var retour=reserve_interne['xhr'].responseXML;if(typeof(configuration['param_fonction_a_executer_reponse'])!="undefined"){eval(configuration['fonction_a_executer_reponse']+'('+configuration['param_fonction_a_executer_reponse']+',retour)')}else{eval(configuration['fonction_a_executer_reponse']+'(retour)')}}return false}}else{document.getElementById(configuration['div_a_modifier']).innerHTML=reserve_interne['xhr'].responseText;if(typeof(configuration['fonction_a_executer_reponse'])!="undefined"){var retour=reserve_interne['xhr'].responseText;if(typeof(configuration['param_fonction_a_executer_reponse'])!="undefined"){eval(configuration['fonction_a_executer_reponse']+'('+configuration['param_fonction_a_executer_reponse']+',retour)')}else{eval(configuration['fonction_a_executer_reponse']+'(retour)')}}}}else{clearTimeout(reserve_interne['xhr_verif_retour_ajax']);if(typeof(configuration['div_wait'])!="undefined"){document.getElementById(configuration['div_wait']).innerHTML=''}if(typeof(configuration['div_wait_nbr_tentative'])!="undefined"){document.getElementById(configuration['div_wait_nbr_tentative']).innerHTML=''}alert(reserve_interne['xhr'].responseText)}}};if(reserve_interne['xhr_nombre_essai']>=configuration['max_tentative']){clearTimeout(reserve_interne['xhr_verif_retour_ajax']);reserve_interne['xhr'].abort();if(typeof(configuration['div_wait'])!="undefined"){document.getElementById(configuration['div_wait']).innerHTML=''}if(typeof(configuration['div_wait_nbr_tentative'])!="undefined"){document.getElementById(configuration['div_wait_nbr_tentative']).innerHTML=''}if(typeof(configuration['fonction_a_executer_cas_non_reponse'])!="undefined"){if(typeof(configuration['param_fonction_a_executer_cas_non_reponse'])!="undefined"){eval(configuration['fonction_a_executer_cas_non_reponse']+'('+configuration['param_fonction_a_executer_cas_non_reponse']+',false)')}else{eval(configuration['fonction_a_executer_cas_non_reponse']+'()')}}alert(get_lib(191));return false}else{reserve_interne['appel_auto']=true;reserve_interne['xhr_verif_retour_ajax']=setTimeout(function(){ajax_call(configuration,reserve_interne)},configuration['delai_tentative'])}}function get_noeud(contenu_xml,noeud){var root_node=contenu_xml.getElementsByTagName(noeud).item(0);return root_node.firstChild.data}function get_json(contenu){var myJsonObject=xml2json.parser(contenu);return myJsonObject}

xml2json={
	parser:function(xmlcode,ignoretags,debug){
		if(!ignoretags){ignoretags=""};
xmlcode=xmlcode.replace(/\s*\/>/g,'/>');
xmlcode=xmlcode.replace(/<\?[^>]*>/g,"").replace(/<\![^>]*>/g,"");
if (!ignoretags.sort){ignoretags=ignoretags.split(",")};
var x=this.no_fast_endings(xmlcode);
x=this.attris_to_tags(x);
x=escape(x);
x=x.split("%3C").join("<").split("%3E").join(">").split("%3D").join("=").split("%22").join("\"");
for (var i=0;i<ignoretags.length;i++){
	x=x.replace(new RegExp("<"+ignoretags[i]+">","g"),"*$**"+ignoretags[i]+"**$*");
x=x.replace(new RegExp("</"+ignoretags[i]+">","g"),"*$***"+ignoretags[i]+"**$*")
};
x='<JSONTAGWRAPPER>'+x+'</JSONTAGWRAPPER>';
	this.xmlobject={};
	var y=this.xml_to_object(x).jsontagwrapper;
	if(debug){y=this.show_json_structure(y,debug)};
	return y
},
xml_to_object:function(xmlcode){
	var x=xmlcode.replace(/<\//g,"§");
x=x.split("<");
var y=[];
var level=0;
var opentags=[];
for (var i=1;i<x.length;i++){
	var tagname=x[i].split(">")[0];
opentags.push(tagname);
level++
y.push(level+"<"+x[i].split("§")[0]);
while(x[i].indexOf("§"+opentags[opentags.length-1]+">")>=0){level--;opentags.pop()}
};
var oldniva=-1;
var objname="this.xmlobject";
for (var i=0;i<y.length;i++){
	var preeval="";
var niva=y[i].split("<")[0];
var tagnamn=y[i].split("<")[1].split(">")[0];
tagnamn=tagnamn.toLowerCase();
var rest=y[i].split(">")[1];
if(niva<=oldniva){
	var tabort=oldniva-niva+1;
	for (var j=0;j<tabort;j++){objname=objname.substring(0,objname.lastIndexOf("."))}
};
objname+="."+tagnamn;
var pobject=objname.substring(0,objname.lastIndexOf("."));
if (eval("typeof "+pobject) != "object"){preeval+=pobject+"={value:"+pobject+"};\n"};
var objlast=objname.substring(objname.lastIndexOf(".")+1);
var already=false;
for (k in eval(pobject)){if(k==objlast){already=true}};
var onlywhites=true;
for(var s=0;s<rest.length;s+=3){
	if(rest.charAt(s)!="%"){onlywhites=false}
};
if (rest!="" && !onlywhites){
if(rest/1!=rest){
	rest="'"+rest.replace(/\'/g,"\\'")+"'";
rest=rest.replace(/\*\$\*\*\*/g,"</");
rest=rest.replace(/\*\$\*\*/g,"<");
rest=rest.replace(/\*\*\$\*/g,">")
	}
} 
else {rest="{}"};
if(rest.charAt(0)=="'"){rest='unescape('+rest+')'};
if (already && !eval(objname+".sort")){preeval+=objname+"=["+objname+"];\n"};
var before="=";after="";
if (already){before=".push(";after=")"};
var toeval=preeval+objname+before+rest+after;
eval(toeval);
if(eval(objname+".sort")){objname+="["+eval(objname+".length-1")+"]"};
		oldniva=niva
	};
	return this.xmlobject
},
show_json_structure:function(obj,debug,l){
	var x='';
if (obj.sort){x+="[\n"} else {x+="{\n"};
for (var i in obj){
	if (!obj.sort){x+=i+":"};
if (typeof obj[i] == "object"){
	x+=this.show_json_structure(obj[i],false,1)
}
else {
	if(typeof obj[i]=="function"){
var v=obj[i]+"";
	x+=v
}
else if(typeof obj[i]!="string"){x+=obj[i]+",\n"}
else {x+="'"+obj[i].replace(/\'/g,"\\'").replace(/\n/g,"\\n").replace(/\t/g,"\\t").replace(/\r/g,"\\r")+"',\n"}
	}
};
if (obj.sort){x+="],\n"} else {x+="},\n"};
if (!l){
	x=x.substring(0,x.lastIndexOf(","));
x=x.replace(new RegExp(",\n}","g"),"\n}");
x=x.replace(new RegExp(",\n]","g"),"\n]");
var y=x.split("\n");x="";
var lvl=0;
for (var i=0;i<y.length;i++){
	if(y[i].indexOf("}")>=0 || y[i].indexOf("]")>=0){lvl--};
tabs="";for(var j=0;j<lvl;j++){tabs+="\t"};
x+=tabs+y[i]+"\n";
if(y[i].indexOf("{")>=0 || y[i].indexOf("[")>=0){lvl++}
};
if(debug=="html"){
x=x.replace(/</g,"&lt;").replace(/>/g,"&gt;");
x=x.replace(/\n/g,"<BR>").replace(/\t/g,"&nbsp;&nbsp;&nbsp;&nbsp;")
};
if (debug=="compact"){x=x.replace(/\n/g,"").replace(/\t/g,"")}
	};
	return x
},
no_fast_endings:function(x){
	x=x.split("/>");
for (var i=1;i<x.length;i++){
	var t=x[i-1].substring(x[i-1].lastIndexOf("<")+1).split(" ")[0];
x[i]="></"+t+">"+x[i]
}	;
x=x.join("");
	return x
},
attris_to_tags: function(x){
	var d=' ="\''.split("");
x=x.split(">");
for (var i=0;i<x.length;i++){
	var temp=x[i].split("<");
for (var r=0;r<4;r++){temp[0]=temp[0].replace(new RegExp(d[r],"g"),"_jsonconvtemp"+r+"_")};
if(temp[1]){
	temp[1]=temp[1].replace(/'/g,'"');
temp[1]=temp[1].split('"');
for (var j=1;j<temp[1].length;j+=2){
	for (var r=0;r<4;r++){temp[1][j]=temp[1][j].replace(new RegExp(d[r],"g"),"_jsonconvtemp"+r+"_")}
};
temp[1]=temp[1].join('"')
};
x[i]=temp.join("<")
};
x=x.join(">");
x=x.replace(/ ([^=]*)=([^ |>]*)/g,"><$1>$2</$1");
x=x.replace(/>"/g,">").replace(/"</g,"<");
for (var r=0;r<4;r++){x=x.replace(new RegExp("_jsonconvtemp"+r+"_","g"),d[r])}	;
		return x
	}
};

if(!Array.prototype.push){
	Array.prototype.push=function(x){
		this[this.length]=x;
		return true
	}
};

if (!Array.prototype.pop){
	Array.prototype.pop=function(){
  		var response = this[this.length-1];
  		this.length--;
  		return response
	}
};
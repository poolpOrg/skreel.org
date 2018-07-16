function Navigateur()
{ this.vrs = navigator.appVersion ;
  this.agt = navigator.userAgent ;
  this.dom = document.getElementById && true ;
  this.op5 = ( this.dom && (window.opera ? true : false) ) ;
  this.ns4 = (!this.dom && document.layers ) ;
  this.ie4 = (!this.dom && document.all ) ;
  this.ie5 = ( this.dom && this.vrs.indexOf("MSIE 5")>-1 && !this.op5 ) ;
  this.ie6 = ( this.dom && this.vrs.indexOf("MSIE 6")>-1 && !this.op5 ) ;
  this.gko = ( this.dom && this.agt.indexOf("Gecko")>-1 ) ;
  this.ns6 = ( this.gko && this.agt.indexOf("Netscape6")>-1 ) ;
  this.who = this.op5 ? 'op5' : this.ns4 ? 'ns4' : this.ie4 ? 'ie4' : this.ie5 ? 'ie5' : this.ie6 ? 'ie6' : this.ns6 ? 'ns6' : this.gko ? 'gko' : false ;
  this.bar = ( this.dom && typeof window.sidebar=="object" && typeof window.sidebar.addPanel=="function" )
} // classe Navigateur

var imagePath	= "http://www.skreel.org/-images/" ;

function setInputStyle( font, border, szArray )
{ if (!navigateur.dom) return; else { for (i=0 ; i<document.forms.length ; i++) for (j=0 ; j<document.forms[i].elements.length ; j++) { O=document.forms[i].elements[j]; if (!navigateur.op5) { O.style.borderStyle=border; } if (O.type=='text'||O.type=='password') { O.size=szArray[i][j]; O.style.font=font; }}} }

function setBackground( bgImage )
{ if ( navigateur.who && !navigateur.ns4 && !navigateur.op5 ) { daBody=document.getElementsByTagName("body")[0]; daBody.style.color="#000000"; daBody.style.width="100%"; daBody.style.height="100%"; daBody.style.backgroundColor="#ffffff"; daBody.style.backgroundImage="url("+ imagePath + bgImage +")"; daBody.style.backgroundAttachment="fixed"; daBody.style.backgroundRepeat="no-repeat"; daBody.style.backgroundPosition="bottom left"; } }

function SKsidebar()
{ if (navigateur.bar) window.sidebar.addPanel("Skreel sidebar","<?= SK_sidebar ?>",""); else window.alert("la sidebar est prevue pour mozilla/netscape6"); }

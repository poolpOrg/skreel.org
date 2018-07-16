function delcheck( name )
{ if (!name) return confirm('confirmation effacer ?'); else return confirm(' confirmation effacer ?\n  " '+name+' "'); }

function open_win( url, w, h, x, y )
{ if (!win||win.closed) win=window.open(url,'POPS','height='+h+',width='+w+',left='+x+',top='+y+',scrollbars,resizable'); else { if (navigateur.gko) { win.close(); open_win( url, w, h, x, y ); } else { win.moveTo(x,y); win.resizeTo(w+36,h+30); win.location=url; win.focus(); }} return false; }

function open_smallWin( url )
{ return open_win(url, 500, 300, 150, 140); }

function open_largeWin( url )
{ return open_win(url, 720, 490, 35, 35); }

function open_deletWin( url , name )
{ if ( delcheck(name) ) { return open_smallWin(url); } else { return false; } }

function isOpener( regExp )
{ return( window.opener!=null && !window.opener.closed && regExp.test(window.opener.location.href) ); }

function logoutOrClose()
{ if (window.opener!=null && !window.opener.closed) { window.close(); return false; } return true; }

function backOrClose( regExp )
{ if ( isOpener(regExp) ) { window.close(); window.opener.location.reload(); return false; } return true; }

function changeClass( nline, newClass )
{ if (!navigateur.dom) return; O=document.getElementById('line'.concat(nline)); if (O.className!='bgRed') O.className=newClass; }

function addBold(txtarea) { txtarea.value+='[B][/B]'; return false; }
function addItlc(txtarea) { txtarea.value+='[I][/I]'; return false; }
function addStrk(txtarea) { txtarea.value+='[S][/S]'; return false; }
function addLink(txtarea) { txtarea.value+='[A HREF="http://"][/A]'; return false; }
function addCode(txtarea) { txtarea.value+='<?php  ?>'; return false; }
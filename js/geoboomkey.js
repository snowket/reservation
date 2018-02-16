lang=0;

eng=new 
Array(97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,87,82,84,83,67,74,90);

geo=new 
Array(4304,4305,4330,4307,4308,4324,4306,4336,4312,4335,4313,4314,4315,4316,4317,4318,4325,4320,4321,4322,4323,4309,4332,4334,4327,4310,4333,4326,4311,4328,4329,4319,4331,91,93,59,39,44,46,96);

function geoboom()
{
for(mtv=0; mtv<=geo.length; mtv++)
{
if (eng[mtv]==event.keyCode)
{
event.keyCode=geo[mtv];}
}
}
function aris(chcode,mas)
{	
for (mtva=0; mtva<mas.length; mtva++)
{
if (mas[mtva]==chcode)
{
return (mtva);
}
}
return (-1);
}

function geo(st)
{
var st1='';
for
(mtv=0; mtv<st.length; mtv++)
{
ch=st.charAt(mtv);
chc=ch.charCodeAt(0);
chi=aris(chc,eng);
if(chi!=-1)
{
st1+=String.fromCharCode(geo[chi]);
}
else {st1+=ch;
}
}
return st1;
}

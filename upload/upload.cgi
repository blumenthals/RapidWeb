#!/usr/bin/perl
use CGI qw/:standard :html3/;
use CGI::Carp;
$q = new CGI;

#Author Rose Kane 12th May 2001 version 1.1. may 20th 2001
# Abridged from  Steven E. Brenner 1996
# $Id: fup.cgi,v 1.2 1996/03/30 01:35:32 brenner Exp $

# Start off by reading and parsing the data.  Save the return value.
# We could also save the file's name and content type, but we don't
# do that in this example.
# revision changes from version 1.0 to 1.1
# file path not shown in web form
# uploaded file replaces original - previously appended
# a url to uploaded file is shown automatically


$site_url = 'http://'.$ENV{HTTP_HOST}.'/';
$root_directory = $ENV{DOCUMENT_ROOT}.'/';
#note root directory should not include www - we have divorced this from the
#root directory for security reasons
# remember to chmod the directory below to 777
$start_directory = 'images/upload';


#dont change below this line
$upload_script = $site_url.'upload/upload.cgi';
$filename = param('upfile');
$info_note = param('note');
$info_upload_dir = $start_directory;
$url_dir = $info_upload_dir;
$info_upload_dir = $root_directory.'images/upload';

# Munge the uploaded text so that it doesn't contain HTML elements
# This munging isn't complete -- lots of illegal characters are left as-is.
# However, it takes care of the most common culprits.  
#$in{'upfile'} =~ s/</&lt;/g;
#$in{'upfile'} =~ s/>/&gt;/g;

$lenfile = (length($filename)); 

if ($lenfile >= 1) {
# Now produce the result: an HTML page...
print "Content-type: text/html\n\n"; 



    $info_outfile = lc($filename);
    $info_outfile =~ s {.*[\:\\\/]} []gos;
    $info_outfile =~ s/[^A-Za-z0-9\._ \-=@\x80-\xFE]/_/go;
    $info_outfile =~ s/ /_/g;

$temp = "";
$upload_url = $site_url.$url_dir.'/'.$info_outfile;
$upload_url =~ s/$temp//;
print <<"EOF";

<html>
<head>
<title>RapidWeb File Upload Form</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
<!--
body, td, th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.style1 {
	font-size: 24px;
	color: #FFFFFF;
}
body {
	background-image: url(/rw-global/images/edit/editpgbg.gif);
	background-repeat: repeat-x;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.buttons {
	width: 140px;
}
.buttons2 {
	width: 160px;
}
-->
</style>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="top"><table width="500" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="197" height="154"><img src="/rw-global/images/edit/logo-blue.gif" alt="Blumenthals RapidWeb" width="176" height="107"><br></td>
          <td colspan="2" align="right"><h1 class="style1">File &amp; Image<br>Upload Successful!</h1></td>
        </tr>
        <tr>
          <td height="74" colspan="2" valign="middle"><form>
              <input type="button" onClick="window.open('$site_url$start_directory/$info_outfile','Uploaded Image',' width=500,height=500, resizable=yes')" value="View $info_outfile">
          </form></td>
		  <td width="168" align="right" valign="middle"><form>
              <input type="button" class="buttons2" onClick="parent.location='upload.cgi'" value="Upload Another Image"><br>
              <input type="button" class="buttons2" onClick="window.close()" value="Close Upload Window"></form></td>
        </tr>
        <tr>
          <td height="98" colspan="3"><p><br>
              The File or Image '$info_outfile' that you <br>
              selected was successfully uploaded.</p>
            <p>You can now copy and paste the following URL into your RapidWeb Page:</p>
            <p><strong>BASIC:</strong><br>
              [$site_url$start_directory/$info_outfile]</p>
            <p align="center"><strong>OR</strong></p>
            <p align="left"><strong>ADVANCED:</strong><br>
              |&lt;img src=$site_url$start_directory/$info_outfile align=&quot;right&quot; alt=&quot;$info_note&quot;&gt;</p>
		    <p align="center"><strong>OR</strong></p>
		    <p align="left"><strong>PDF File:</strong><br>
		      [$info_note (pdf)|$site_url$start_directory/$info_outfile]</p></td>
        </tr>
        <tr>
          <td height="0"></td>
          <td width="135" height="0"></td>
          <td height="0"></td>
        </tr>
        <tr>
          <td height="1"></td>
          <td height="1" colspan="2"></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
EOF

# Do the actual work.
    (open INFO,">$info_upload_dir/$info_outfile");

    
        while ($bytes = read($filename,$data,1024)) {
                $length_info += $bytes;
                print INFO $data;
        }
	 close(INFO);
}

else
{

print "Content-type: text/html\n\n"; 
print << 'EOF';
<html>
<head>
<title>RapidWeb File Upload Form</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
<!--
body, td, th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.style1 {
	font-size: 24px;
	color: #FFFFFF;
}
body {
	background-image: url(/rw-global/images/edit/editpgbg.gif);
	background-repeat: repeat-x;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.buttons {
	width: 80px;
}
-->
</style>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="top"><form method='POST' enctype='multipart/form-data'>
        <table width="500" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="187" height="154"><img src="/rw-global/images/edit/logo-blue.gif" alt="Blumenthals RapidWeb" width="176" height="107"><br></td>
            <td colspan="2" align="right"><h1 class="style1">File &amp; Image<br>Upload Page</h1></td>
          </tr>
          <tr>
            <td height="62" colspan="2" valign="middle"><strong>To Upload:<br>
              </strong>1. Select the file with the browse button.<br>
              2. Fill in the description (1-5 words).<br>
              3. Click the Upload Button. </td>
            <td width="197" height="62" align="right" valign="middle"><input type=submit class="buttons" value=Upload File><br>
            <input type=reset class="buttons" value=Clear Fields><input type="button" class="buttons2" onClick="window.close()" value="Cancel"></td>
          </tr>
          <tr>
            <td height="98" colspan="3"><br>
              File to upload:<br>
              <input name=upfile type=file size="45">
              <br>
              <br>
              File Description:<br>
              <input name=note type=text size="70" style="width:100%">
              <input type=hidden name=fileupload size = 40 value=images/upload>
              <br><br>
              <center>
              <input type="button" class="buttons2" onClick="window.close()" value="Cancel">
              <input type=reset class="buttons" value=Clear fields>
              <input type=submit class="buttons" value=Upload file></center></td>
          </tr>
          <tr>
            <td height="1"></td>
            <td width="116" height="1"></td>
            <td height="1"></td>
          </tr>
        </table>
      </form></td>
  </tr>
</table>
</body>
</html>
EOF

 
}



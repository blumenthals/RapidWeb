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


$site_url = "http://www.website.com/";
$root_directory = '/home/user/public_html/';
#note root directory should not include www - we have divorced this from the
#root directory for security reasons
# remember to chmod the directory below to 777
$start_directory = 'images/upload';


#dont change below this line
$upload_script = $site_url.'upload/upload.cgi';
$filename = param('upfile');
$info_upload_dir = param('fileupload');
$url_dir = $info_upload_dir;
$info_upload_dir = $root_directory.$info_upload_dir;


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

print "<HTML>";
$temp = "";
$upload_url = $site_url.$url_dir.'/'.$info_outfile;
$upload_url =~ s/$temp//;
print "<blockquote><img src=http://rapidweb.blumenthals.com/rapidweb.gif><br><br>";
print "<h2>Congratulations!</h2><br>";
print "<font face=verdana,helvetica size=2><p> You uploaded $filename<br><br><a href=$upload_url target=_blank>View uploaded File</a> (<i>note: image will appear in a new window</i>)<br><br>";
print "You can copy & paste the following URL into your RapidWeb Page:<br><br>";
print "<b>BASIC:</b>  [$upload_url]<br><br>";
print "<b>ADVANCED:</b>  |&lt;img src=$upload_url align=right&gt;</font></blockquote>";
print "<center><form><input type=button value= Upload_Another_Image onClick=history.go(-1)
name=back></form> ";
print "<center><form><input type=button value= Back_To_Edit_Page onClick=history.go(-2)
name=back></form><br><br>";


    (open INFO,">$info_upload_dir/$info_outfile");

    
        while ($bytes = read($filename,$data,1024)) {
                $length_info += $bytes;
                print INFO $data;
        }
	 close(INFO);
print "</HTML>";
}

else
{

print "Content-type: text/html\n\n"; 
print "<HTML>";
print "<head>";
print "<title>RapidWeb File Upload Form</title>";
print "</head>";
print "<body>";
print "<blockquote><img src=http://rapidweb.blumenthals.com/rapidweb.gif><br><br>";
print "<hr>";
print "<h2>Please fill in the file-upload form below</h2>";
print "<form method='POST' enctype='multipart/form-data'";
print " action=$upload_script>";
print "File to upload: <input type=file name=upfile><br>";
print "Notes about the file: <input type=text name=note><br>";
print "<br>";
print "<input type=hidden name=fileupload size = 40 value"; 
print "=$start_directory> <br><br>";
print "<input type=submit value=Press> to upload the file!<br><br>";
print "<input type=reset value=Clear> form and start over.";
print "</form>";
print "<hr></blockquote>";
print "</body>";
print "</html>";
 
}



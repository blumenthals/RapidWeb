<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Edit: ###PAGEURL###</title>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/../default/switchcontent.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/../default/rollovers.js"></script>
<link href="<?php bloginfo('template_directory'); ?>/../default/style.css" rel="stylesheet" type="text/css"/>
</head>
<body bgcolor="#FFFFFF" text="#000033" link="#000066" vlink="#003399" alink="#003399" onLoad="MM_preloadImages('<?php bloginfo('template_directory'); ?>/../default/admin/upload-over.gif','<?php bloginfo('template_directory'); ?>/../default/admin/arrow-over.gif','<?php bloginfo('template_directory'); ?>/../default/admin/meta_tags-over.gif')">
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="top"><table width="775" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><form method="POST" action="###SCRIPTURL###">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="26%" height="155" align="left" valign="middle"><a href="###SCRIPTURL###"><img src="<?php bloginfo('template_directory'); ?>/../default/admin/logo-blue.gif" hspace="8" border=0 align="absmiddle"></a></td>
                  <td height="155" colspan="2" align="left" valign="middle"><h1><span class="headertitle"> Edit ###PAGEURL###</span></h1></td>
                </tr>
                <tr>
                  <td height="60" colspan="2">
                  <a onClick="window.open('rw-admin/upload.php','ImageUpload',' width=551, height=494, resizable=yes')" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('upload','','<?php bloginfo('template_directory'); ?>/../default/admin/upload-over.gif',1)"><img src="<?php bloginfo('template_directory'); ?>/../default/admin/upload.gif" alt="Upload an Image from your computer" name="upload" width="102" height="49" border="0"></a>
                  <a onClick="window.open('###SCRIPTURL###?settings','Settings',' width=551, height=494, resizable=yes')" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('edit_meta_tags','','<?php bloginfo('template_directory'); ?>/../default/admin/meta_tags-over.gif',1)"><img src="<?php bloginfo('template_directory'); ?>/../default/admin/meta_tags.gif" alt="Edit Default Meta Tags" name="edit_meta_tags" width="102" height="49" border="0"></a></td> 
                  <td width="55%" align="right"><input type="submit" value=" Save "><br>
                    <input type="button" value="Cancel" onClick="history.go(-1)" name="back2"></td>
                </tr>
              </table>
              <br>
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td align="center"><textarea name="content" rows="22" wrap="virtual" class="txtfield">###CONTENT###</textarea></td>
                </tr>
                <tr>
                  <td align="left"><div class="section"><h3 id="advancedediting2-title" class="handcursor"></h3></div>
              <div id="advancedediting2" class="switchgroup2">
                <table width="775" border="0" cellpadding="5" cellspacing="0" bgcolor="#f6f4e7">
                  <tr>
                    <td width="70" valign="top"><strong>Title:</strong></td>
                    <td><input name='title' type=text class="txtfield" value='###USERTITLE###'>                    </td>
                  </tr>
                  <tr>
                    <td width="70" valign="top"><strong>Meta Description </strong> </td>
                    <td><textarea name='meta' rows=2 class="txtfield">###META###</textarea>                    </td>
                  </tr>
                  <tr>
                    <td width="70" valign="top"><strong>Meta Keywords </strong> </td>
                    <td><textarea name='metakeywords' rows=2 class="txtfield">###METAKEYWORDS###</textarea>                    </td>
                  </tr>
                  <tr>
                    <td width="70" valign="top"><strong>Special Variables </strong> </td>
                    <td><textarea name='variables' rows=2 class="txtfield">###VARIABLES###</textarea>                    </td>
                  </tr>
                  <tr>
                    <td width="70" valign="top"><strong>Page Template </strong> </td>
                    <td><select name='template'>###TEMPLATESELECT###</select>                    </td>
                  </tr>
                  <tr>
                    <td width="70" valign="top"><strong>Don't Index This Page </strong> </td>
                    <td><input type='checkbox' value='true' name='noindex' ###NOINDEX###></td></tr>
                </table>
              </div>
            <script type="text/javascript">
            //   MAIN FUNCTION: new switchcontent("class name", "[optional_element_type_to_scan_for]") REQUIRED
            //1) Instance.setStatus(openHTML, closedHTML)- Sets optional HTML to prefix the headers to indicate open/closed states
            //2) Instance.setColor(openheaderColor, closedheaderColor)- Sets optional color of header when it's open/closed
            //3) Instance.setPersist(true/false)- Enables or disabled session only persistence (recall contents' expand/contract states)
            //4) Instance.collapsePrevious(true/false)- Sets whether previous content should be contracted when current one is expanded
            //5) Instance.defaultExpanded(indices)- Sets contents that should be expanded by default (ie: 0, 1). Persistence feature overrides this setting!
            //6) Instance.init() REQUIRED
            var advancedediting=new switchcontent("switchgroup2", "div") //Limit scanning of switch contents to just "div" elements
            advancedediting.setStatus('<img src="<?php bloginfo('template_directory'); ?>/../default/admin/arrow-over.gif" align="absmiddle"/> Less Page Settings', '<img src="<?php bloginfo('template_directory'); ?>/../default/admin/arrow-down.gif" align="absmiddle"/> More Page Settings (Meta Tags, Variables, Template)')
            advancedediting.setColor('000000', 'c54808')
            advancedediting.setPersist(false)
            advancedediting.collapsePrevious(true) //Only one content open at any given time
            advancedediting.init()
            </script></td>
                </tr>
                <tr>
                  <td align="right"><input type="button" value="Cancel" onClick="history.go(-1)" name="back"><input type="submit" value=" Save " name="submit"></td>
                </tr>
              </table>
              
                          <input type="hidden" name="post" value="###PAGEURL###">
              <input type="hidden" name="editversion" value="###VERSION###">
            </form>
            <table width="775" border="0" cellpadding="5" cellspacing="0">
              <tr>
                <td colspan="4" valign="top" class="editing"><h3>Editing commands:</h3></td>
              </tr>
              <tr>
                <td width="70" valign="top" bgcolor="#f6f4e7" class="editing"><strong>Item:</strong></td>
                <td width="265" valign="top" bgcolor="#f6f4e7" class="style1">Description:</td>
                <td valign="top" bgcolor="#f6f4e7" class="style1">Syntax:</td>
                <td valign="top" bgcolor="#f6f4e7" class="style1">Result:</td>
              </tr>
              <tr>
                <td width="70" valign="top" class="editing"><strong>Headings:</strong></td>
                <td valign="top" class="editing">One exclamation point before a word or group 
                  of words makes headings. Can combine 2 or 3 for larger headings .</td>
                <td width="200" valign="top" class="editing">!Heading1<br>
                  !!Heading2<br>
                  !!!Heading3</td>
                <td width="200" valign="top" class="editing"><h3>Heading1</h3>
                  <br>
                  <h2>Heading2</h2>
                  <br>
                  <h1>Heading3</h1></td>
              </tr>
              <tr>
                <td width="70" valign="top" bgcolor="#f6f4e7" class="editing"><strong>Emphasis:</strong></td>
                <td valign="top" bgcolor="#f6f4e7" class="editing">2 single quotes ('') before and after a word or sentence for 
                  italics and 2 underscores (__)before and after a word or sentence for bold. They 
                  can be combined.</td>
                <td width="200" valign="top" bgcolor="#f6f4e7" class="editing">''italics''<br>
                  __bold__<br>
                  ''__italics &amp; bold__''</td>
                <td width="200" valign="top" bgcolor="#f6f4e7" class="editing"><em>italics</em><br>
                  <strong>bold</strong><br>
                  <strong><em>italics &amp; bold</em></strong></td>
              </tr>
              <tr>
                <td width="70" valign="top" class="editing"><strong>Linebreaks:</strong></td>
                <td valign="top" class="editing"> Use 3 Percent Symbols (%%%) makes a linebreak. This works in lists and headings as well.</td>
                <td valign="top" class="editing">Lorem ipsum dolor sit.<br>
                  %%%<br>
                  consectetuer adipiscing elit.</td>
                <td valign="top" class="editing">Lorem ipsum dolor sit.<br>
                  consectetuer adipiscing elit.</td>
              </tr>
              <tr>
                <td width="70" valign="top" bgcolor="#f6f4e7" class="editing"><strong>Simple Image: </strong></td>
                <td valign="top" bgcolor="#f6f4e7" class="editing">To add an image include the url in square brackets.([ ]) (NOTE: there are no linebreaks)</td>
                <td valign="top" bgcolor="#f6f4e7" class="editing"><p>[http://www.blumenthals.com/<br>
                    images/upload/logo2.gif]<br>
                  </p></td>
                <td valign="top" bgcolor="#f6f4e7" class="editing"><img src="<?php bloginfo('template_directory'); ?>/../default/admin/logo1.gif" width="172" height="41"></td>
              </tr>
              <tr>
                <td width="70" valign="top" class="style1">Advanced Image:</td>
                <td valign="top" class="editing">Use standard html markup. By changing the align value from right to left you can adjust the placement. Adding an alt value will allow the image to be picked up by search engines &amp; screen readers (for the impaired) (NOTE: there are no linebreaks)</td>
                <td valign="top" class="editing">|&lt;img src =http://www.blumenthals.com/<br>
                  images/upload/logo3.gif align=&quot;right&quot; alt=&quot;RapidWeb&quot;&gt;</td>
                <td valign="top" class="editing"><img src="<?php bloginfo('template_directory'); ?>/../default/admin/logo2.gif" alt="RapidWeb" width="174" height="59" align="right"></td>
              </tr>
              <tr>
                <td width="70" valign="top" bgcolor="#f6f4e7" class="editing"><strong>Links:</strong></td>
                <td valign="top" bgcolor="#f6f4e7" class="editing">Use square brackets around a word to create link to a new page. 
                  For an outside link, (http://www.etc.com) Type the name of the link, a pipe (|), followed by URL in side of square brackets.</td>
                <td width="200" valign="top" bgcolor="#f6f4e7" class="editing"> [NewPageName]<br>
                  [External Page
                  | http://cool.wiki.int/]</td>
                <td width="200" valign="top" bgcolor="#f6f4e7" class="editing"><a href="/index.php?home">NewPageName</a><br>
                  <a href="http://cool.wiki.int/">External Page</a></td>
              </tr>
            </table>
            <h3 id="advancedediting1-title" class="handcursor"></h3>
            <div id="advancedediting1" class="switchgroup1">
              <table width="775" border="0" cellpadding="5" cellspacing="0">
                <tr>
                  <td width="70" valign="top" class="style1">&nbsp;</td>
                  <td width="265" valign="top" class="editing">&nbsp;</td>
                  <td width="200" valign="top" class="editing">&nbsp;</td>
                  <td width="200" valign="top" class="editing">&nbsp;</td>
                </tr>
                <tr>
                  <td width="70" valign="top" class="style1">PDF:</td>
                  <td valign="top" class="editing">To link to a pdf use the same technique as an External Page link. (NOTE: there are no linebreaks)</td>
                  <td width="200" valign="top" class="editing"><p>[Rapid Web Brochure (pdf)
                      | http://www.rapidweb.info/upload/<br>
                      images/rapidweblit.pdf]</p></td>
                  <td width="200" valign="top" class="editing"><a href="http://www.rapidweb.info/upload/images/rapidweblit.pdf">RapidWeb Brochure (pdf)</a></td>
                </tr>
                <tr>
                  <td width="70" valign="top" bgcolor="#f6f4e7" class="style1">Mail links:</td>
                  <td valign="top" bgcolor="#f6f4e7" class="editing">You can add a link that loads the visitors default mail client with your email address by creating a normal link &amp; adding mailto: before their address.</td>
                  <td width="200" valign="top" bgcolor="#f6f4e7" class="editing">[mike@blumenthals.com | mailto:mike@blumenthals.com]</td>
                  <td width="200" valign="top" bgcolor="#f6f4e7" class="editing"><a href="mailto:mike@blumenthals.com">mike@blumenthals.com</a></td>
                </tr>
                <tr>
                  <td width="70" valign="top" class="editing"><strong>Lists:</strong></td>
                  <td valign="top" class="editing"> Place an asterick (*) in front of the line for first level of indent for bullet lists.  Place two astericks (**) for second level, three for the third, etc. Bullets and indents are created automatically at each level. Use the pound symbol (#) to create numbered lists. # &amp; * may be mixed at will.</td>
                  <td width="200" valign="top" class="editing"><p>*level 1 Content<br>
                      **level 2 Content<br>
                      ***level 3<br>
                      <br>
                      #Numbered Content<br>
                      #Numbered Content<br>
                      #Numbered Content</p></td>
                  <td width="200" valign="top" class="editing"><ul>
                      <li>level 1 Content</li>
                      <ul>
                        <li>level 2 Content</li>
                        <ul>
                          <li>level 3</li>
                        </ul>
                      </ul>
                    </ul>
                    <ol>
                      <li>Numbered Content<br>
                      </li>
                      <li>Numbered Content</li>
                      <li>Numbered Content</li>
                    </ol></td>
                </tr>
                <tr>
                  <td width="70" valign="top" class="editing"><strong>HTML:</strong></td>
                  <td valign="top" class="editing">If you would like to use a single line of html preceed the line with a pipe.(|)<br>
                    <br>
                    <br>
                    If you would like to use several lines of html, preceed the block with STARTHTML and end the block with ENDHTML <br>
                    <br>
                    (NOTE: there are no linebreaks)</td>
                  <td width="200" valign="top" class="editing"><p>|&lt;img src=&quot;<?php bloginfo('template_directory'); ?>/../default/admin/logo2.gif&quot; width=&quot;172&quot; height=&quot;41&quot;&gt;</p>
                    <p><br>
                      STARTHTML<br>
                      &lt;img src=&quot;<?php bloginfo('template_directory'); ?>/../default/admin/logo2.gif&quot; width=&quot;172&quot; height=&quot;41&quot;&gt;<br>
                      &lt;img src=&quot;<?php bloginfo('template_directory'); ?>/../default/admin/logo3.gif&quot; width=&quot;174&quot; height=&quot;59&quot;&gt;<br>
                      ENDHTML</p></td>
                  <td width="200" valign="top" class="editing"><img src="<?php bloginfo('template_directory'); ?>/../default/admin/logo1.gif" width="172" height="41"><br>
                    <br>
                    <img src="<?php bloginfo('template_directory'); ?>/../default/admin/logo1.gif" width="172" height="41"><br>
                    <img src="<?php bloginfo('template_directory'); ?>/../default/admin/logo2.gif" width="174" height="59"></td>
                </tr>
                <tr>
                  <td width="70" valign="top" bgcolor="#f6f4e7" class="editing"><strong>Indented Paragraphs:</strong></td>
                  <td valign="top" bgcolor="#f6f4e7" class="editing">If you want to have quotes or other blocked, indented paragraphs in your copy, preceed the paragraph with a semicolon and a colon. (;:)</td>
                  <td width="200" valign="top" bgcolor="#f6f4e7" class="editing"><p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vestibulum tincidunt.<br>
                      <br>
                      ;:Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vestibulum tincidunt.<br>
                      <br>
                      Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vestibulum tincidunt.</p></td>
                  <td width="200" valign="top" bgcolor="#f6f4e7" class="editing">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vestibulum tincidunt.<br>
                    <ul>
                      Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vestibulum tincidunt.
                    </ul>
                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vestibulum tincidunt.</td>
                </tr>
                <tr>
                  <td width="70" valign="top" class="editing"><strong>Additional Symbols:</strong></td>
                  <td valign="top" class="editing">Here are some common symbols that you can copy &amp; paste into your text. Please note that some of these symbols may not be viewable on some computers.</td>
                  <td colspan="2" valign="middle" class="symbols">&copy; &reg; &trade; &frac14; &frac12; &frac34; &trade; &permil; &#9658; &#9668; &deg; &radic; &sup1; &sup2; &sup3; &#9834; &#9835;</td>
                </tr>
                <tr>
                  <td width="70" valign="top" bgcolor="#f6f4e7" class="editing"><strong>Additional Information:</strong></td>
                  <td colspan="3" valign="top" bgcolor="#f6f4e7" class="editing"><a href="http://www.rapidweb.info/index.php?TextFormattingRules" target="_blank"><b>TextFormattingRules</b></a>: Provides a detailed explanation of the above formatting techniques.<br>
                    <a href="http://www.rapidweb.info/index.php?SandBox" target="_blank"><b>SandBox: </b></a>Provides a tutorial on the basics of entry in the text edit box</td>
                </tr>
              </table>
            </div>
            <script type="text/javascript">
            //   MAIN FUNCTION: new switchcontent("class name", "[optional_element_type_to_scan_for]") REQUIRED
            //1) Instance.setStatus(openHTML, closedHTML)- Sets optional HTML to prefix the headers to indicate open/closed states
            //2) Instance.setColor(openheaderColor, closedheaderColor)- Sets optional color of header when it's open/closed
            //3) Instance.setPersist(true/false)- Enables or disabled session only persistence (recall contents' expand/contract states)
            //4) Instance.collapsePrevious(true/false)- Sets whether previous content should be contracted when current one is expanded
            //5) Instance.defaultExpanded(indices)- Sets contents that should be expanded by default (ie: 0, 1). Persistence feature overrides this setting!
            //6) Instance.init() REQUIRED
            var advancedediting=new switchcontent("switchgroup1", "div") //Limit scanning of switch contents to just "div" elements
            advancedediting.setStatus('<img src="<?php bloginfo('template_directory'); ?>/../default/admin/arrow-over.gif" align="absmiddle"/> Less Editing Techniques', '<img src="<?php bloginfo('template_directory'); ?>/../default/admin/arrow-down.gif" align="absmiddle"/> More Editing Techniques')
            advancedediting.setColor('000000', 'c54808')
            advancedediting.setPersist(false)
            advancedediting.collapsePrevious(true) //Only one content open at any given time
            advancedediting.init()
            </script>
            <br>
            <br>
            <br>
            <br></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>

<?php

class WikiPage extends RWPlugin implements RWPageType {
    public function __construct(RapidWeb $rapidweb) {
        parent::__construct($rapidweb);
        $rapidweb->registerPagetype('page', $this);
    }

    public function getPageTypeName() {
        return 'Page';
    }

    public function do_editor_head() {
        // Do nothing
    }

    public function the_editor_content(View $view) {
      ?>
      <form method="POST" action="<?php echo $view->getScriptURL(); ?>">
        <?php /* Main editor */ ?>
        <textarea name="content" rows="22" wrap="virtual" class="txtfield">Please wait while the editor loads...</textarea>


        <?php /* Save and cancel controls */ ?>
        <div style="float: right; height: 40px;">
          <button class="cancel" name='cancel' onclick="history.go(-1)"></button>
          <button class="save" name='save'></button>
        </div>

        <input type="hidden" name="post" value="<?php echo $view->page->pagename; ?>">
        <input type="hidden" name="editversion" value="<?php echo $view->page->version; ?>">
      </form>
        <section class='details-box' style="clear:both;">
          <h3 class='details-box-show'>
            <img src="<?php bloginfo('template_directory'); ?>/../default/admin/plus.png" align="absmiddle" class="show_details"/> Show Editing Commands
          </h3>
          <h3 class='details-box-hide'>
            <img src="<?php bloginfo('template_directory'); ?>/../default/admin/minus.png" align="absmiddle" class="show_details"/> Hide Editing Commands
          </h3>
          <div class='details'>

        <table width="100%">
          <tr>
            <td class="header">Item:</td>
            <td class="header">Description:</td>
            <td class="header">Syntax:</td>
            <td class="header">Result:</td>
          </tr>

          <tr class="green_bar">
            <td class="label">Headings:</td>
            <td>One exclamation point before a word or group of words makes headings. Can combine 2 or 3 for larger headings .</td>
            <td>!Heading1<br>
              !!Heading2<br>
              !!!Heading3
            </td>
            <td><h3>Heading1</h3>
              <h2>Heading2</h2>
              <h1>Heading3</h1>
            </td>
          </tr>

          <tr>
            <td class="label">Emphasis:</td>
            <td>2 single quotes ('') before and after a word or sentence for 
              italics and 2 underscores (__)before and after a word or sentence for bold. They 
              can be combined.
            </td>
            <td>''italics''<br>
              __bold__<br>
              ''__italics &amp; bold__''
            </td>
            <td><em>italics</em><br>
              bold<br>
              <em>italics &amp; bold</em>
            </td>
          </tr>

          <tr class="green_bar">
            <td class="label">Linebreaks:</td>
            <td> Use 3 Percent Symbols (%%%) makes a linebreak. This works in lists and headings as well.</td>
            <td>Lorem ipsum dolor sit.<br>
              %%%<br>
              consectetuer adipiscing elit.
            </td>
            <td>Lorem ipsum dolor sit.<br>
              consectetuer adipiscing elit.
            </td>
          </tr>

          <tr>
            <td class="label">Simple Image: </td>
            <td>To add an image include the url in square brackets.([ ]) (NOTE: there are no linebreaks)</td>
            <td>
              <p>[http://www.blumenthals.com/<br>
                images/upload/logo2.gif]<br>
              </p>
            </td>
            <td><img src="<?php bloginfo('template_directory'); ?>/../default/admin/logo1.gif" width="172" height="41"></td>
          </tr>

          <tr class="green_bar">
            <td class="label">Advanced Image:</td>
            <td>Use standard html markup. By changing the align value from right to left you can adjust the placement. Adding an alt value will allow the image to be picked up by search engines &amp; screen readers (for the impaired) (NOTE: there are no linebreaks)</td>
            <td>|&lt;img src =http://www.blumenthals.com/<br>
              images/upload/logo3.gif align=&quot;right&quot; alt=&quot;RapidWeb&quot;&gt;</td>
            <td><img src="<?php bloginfo('template_directory'); ?>/../default/admin/logo2.gif" alt="RapidWeb" width="174" height="59"></td>
          </tr>

          <tr>
            <td class="label">Links:</td>
            <td>Use square brackets around a word to create link to a new page. 
              For an outside link, (http://www.etc.com) Type the name of the link, a pipe (|), followed by URL in side of square brackets.
            </td>
            <td> [NewPageName]<br>
              [External Page
              | http://cool.wiki.int/]
            </td>
            <td><a href="/index.php?home">NewPageName</a><br>
              <a href="http://cool.wiki.int/">External Page</a>
            </td>
          </tr>
                        <tr class="green_bar">
                <td class="label">PDF:</td>
                <td>To link to a pdf use the same technique as an External Page link. (NOTE: there are no linebreaks)</td>
                <td><p>[Rapid Web Brochure (pdf)
                    | http://www.rapidweb.info/upload/<br>
                    images/rapidweblit.pdf]</p></td>
                <td><a href="http://www.rapidweb.info/upload/images/rapidweblit.pdf">RapidWeb Brochure (pdf)</a></td>
              </tr>

              <tr>
                <td class="label">Mail links:</td>
                <td>You can add a link that loads the visitors default mail client with your email address by creating a normal link &amp; adding mailto: before their address.</td>
                <td>[mike@blumenthals.com | mailto:mike@blumenthals.com]</td>
                <td><a href="mailto:mike@blumenthals.com">mike@blumenthals.com</a></td>
              </tr>

              <tr class="green_bar">
                <td class="label">Lists:</td>
                <td> Place an asterick (*) in front of the line for first level of indent for bullet lists.  Place two astericks (**) for second level, three for the third, etc. Bullets and indents are created automatically at each level. Use the pound symbol (#) to create numbered lists. # &amp; * may be mixed at will.</td>
                <td><p>*level 1 Content<br>
                  **level 2 Content<br>
                  ***level 3<br>
                  <br>
                  #Numbered Content<br>
                  #Numbered Content<br>
                  #Numbered Content</p>
                </td>
                <td>
                  <ul>
                    <li>level 1 Content
                      <ul>
                        <li>level 2 Content
                          <ul>
                            <li>level 3</li>
                          </ul>
                        </li>
                      </ul>
                    </li>
                  </ul>
                  <ol>
                    <li>Numbered Content</li>
                    <li>Numbered Content</li>
                    <li>Numbered Content</li>
                  </ol>
                </td>
              </tr>

              <tr>
                <td class="label">HTML:</td>
                <td>If you would like to use a single line of html preceed the line with a pipe.(|)<br>
                  <br>
                  <br>
                  If you would like to use several lines of html, preceed the block with STARTHTML and end the block with ENDHTML <br>
                  <br>
                  (NOTE: there are no linebreaks)
                </td>
                <td><p>|&lt;img src=&quot;<?php bloginfo('template_directory'); ?>/../default/admin/logo2.gif&quot; width=&quot;172&quot; height=&quot;41&quot;&gt;</p>
                  <p><br>
                    STARTHTML<br>
                    &lt;img src=&quot;<?php bloginfo('template_directory'); ?>/../default/admin/logo2.gif&quot; width=&quot;172&quot; height=&quot;41&quot;&gt;<br>
                    &lt;img src=&quot;<?php bloginfo('template_directory'); ?>/../default/admin/logo3.gif&quot; width=&quot;174&quot; height=&quot;59&quot;&gt;<br>
                    ENDHTML
                  </p>
                </td>
                <td><img src="<?php bloginfo('template_directory'); ?>/../default/admin/logo1.gif" width="172" height="41"><br>
                  <br>
                  <img src="<?php bloginfo('template_directory'); ?>/../default/admin/logo1.gif" width="172" height="41"><br>
                  <img src="<?php bloginfo('template_directory'); ?>/../default/admin/logo2.gif" width="174" height="59">
                </td>
              </tr>

              <tr class="green_bar">
                <td class="label">Indented Paragraphs:</td>
                <td>If you want to have quotes or other blocked, indented paragraphs in your copy, preceed the paragraph with a semicolon and a colon. (;:)</td>
                <td><p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vestibulum tincidunt.<br>
                    <br>
                    ;:Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vestibulum tincidunt.<br>
                    <br>
                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vestibulum tincidunt.</p></td>
                <td>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vestibulum tincidunt.<br>
                  <ul>
                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vestibulum tincidunt.
                  </ul>
                  Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vestibulum tincidunt.
                </td>
              </tr>

              <tr>
                <td class="label">Additional Symbols:</td>
                <td>Here are some common symbols that you can copy &amp; paste into your text. Please note that some of these symbols may not be viewable on some computers.</td>
                <td colspan="2" valign="middle" class="symbols">&copy; &reg; &trade; &frac14; &frac12; &frac34; &trade; &permil; &#9658; &#9668; &deg; &radic; &sup1; &sup2; &sup3; &#9834; &#9835;</td>
              </tr>

              <tr class="green_bar">
                <td class="label">Additional Information:</td>
                <td colspan="3"><a href="http://www.rapidweb.info/index.php?TextFormattingRules" target="_blank"><b>TextFormattingRules</b></a>: Provides a detailed explanation of the above formatting techniques.<br>
                  <a href="http://www.rapidweb.info/index.php?SandBox" target="_blank"><b>SandBox: </b></a>Provides a tutorial on the basics of entry in the text edit box
                </td>
              </tr>

        </table>
          </div>
        </section>
      <?php
    }

    public function do_head() {
    }

    public function the_content($page) {
	echo $page->getParser()->parse($page->head);
        echo "###CONTENT###";
	echo $page->getParser()->parse($page->foot);
    }

    public function the_title() {
        echo "###TITLE###";
    }
}

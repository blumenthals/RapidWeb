build: css/rapidweb.css ../rw-content/templates/default/style.css
	
css/%.css: less/%.less
	recess --compile $< > $@

%.css: %.less
	recess --compile $< > $@

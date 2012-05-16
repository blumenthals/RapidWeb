build: css/rapidweb.css ../rw-content/templates/default/style.css

less/plugins.less: ../rw-content/plugins/*/plugin.less
	for I in $<; do [ ! -z "$$I" ] && echo '@import "'$$I'";'; done > $@

css/rapidweb.css: less/rapidweb.less less/plugins.less
	recess --compile $< > $@
	
css/%.css: less/%.less
	recess --compile $< > $@

%.css: %.less
	recess --compile $< > $@

 #!/usr/local/bin/newlisp
 ; button-demo.lsp - demonstrate the button control
  
 ; initialization
 (load (append (env "NEWLISPDIR") "/guiserver.lsp")) 

 (gs:init) 
  
 ; describe the GUI
 (gs:frame 'ButtonDemo 100 100 800 480 "Button demo")
 (gs:set-resizable 'ButtonDemo nil)

 (gs:panel 'ColorPanel 750 300)
 (gs:set-color 'ColorPanel (random) (random) (random))
 (gs:button 'aButton 'abutton-action "color")
; (gs:draw-circle 'circle 100 50 5)

 (gs:set-flow-layout 'ButtonDemo "center" 2 15)
 (gs:add-to 'ButtonDemo 'ColorPanel)
 (gs:add-to 'ButtonDemo 'aButton)

 (gs:set-visible 'ButtonDemo true)
  
 ; define actions
 (define (abutton-action id)
     (gs:set-color 'ColorPanel (random) (random) (random)))
  
 ; listen for incoming action requests and dispatch
 (gs:listen)
  
 ; eof 

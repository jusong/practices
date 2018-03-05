#!/usr/bin/newlisp
; button-demo.lsp - demonstrate the button control
 
; initialization
(load (append (env "NEWLISPDIR") "/guiserver.lsp")) 

(gs:init) 
 
; describe the GUI
(gs:frame 'ButtonDemo 100 100 400 300 "Button demo")
(gs:set-resizable 'ButtonDemo nil)
(gs:panel 'ColorPanel 360 200)
(gs:set-color 'ColorPanel (random) (random) (random))
(gs:button 'aButton 'abutton-action "color")
(gs:set-flow-layout 'ButtonDemo "center" 2 15)
(gs:add-to 'ButtonDemo 'ColorPanel 'aButton)
(gs:set-visible 'ButtonDemo true)
 
; define actions
(define (abutton-action id)
    (gs:set-color 'ColorPanel (random) (random) (random)))
 
; listen for incoming action requests and dispatch
(gs:listen)
 
; eof 

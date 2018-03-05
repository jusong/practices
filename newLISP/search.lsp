(set 'file (open "init.lsp" "read"))
(search file "define")
(print (read-line file) "\n")
(close file)

(set 'file (open "program.c" "r"))
(while (search file "#define (.*)" true 0) (println $1))
(close file)
(exit)

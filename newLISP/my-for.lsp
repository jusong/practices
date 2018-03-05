(define-macro (my-for _init _cond _step)
  (let ((_body (cons 'begin (args))))
	(eval (begin 
			_init
			(expand '(while _cond (begin _body _step)) '_cond '_step '_body)))))
(setq i 1)
(my-for (set 'i 1) (< i 9) (inc i)
	(my-for (set 'j 1) (< j i) (inc j)
		(print i "x" j "=" (* i i) " "))
	(println ""))

(exit)

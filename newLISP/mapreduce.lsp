(when (< (sys-info -2) 10111)
  (constant (global 'term) name))

(define remote true)

(if remote
	(set 'docs '(
		; A Comedy of Masks - Ernest Dowson and Arthur Moore, 547KB
		"http://www.gutenberg.org/files/16703/16703.txt"  
		; The Adventures of Sherlock Holmes - Conan Doyle, 576KB
		"http://www.gutenberg.org/cache/epub/1661/pg1661.txt"
		; The tale of Beowulf - anonymous, 219KB
		"http://www.gutenberg.org/files/20431/20431-8.txt" 
		))

	; when running local copies of the text files, then place them
	; in a subdirectory 'mrdemo' of the current working directory
	(set 'docs '(
		"mrdemo/Comedy.txt"
		"mrdemo/Sherlock.txt"
		"mrdemo/Beowulf.txt"
	))
)

(set 'nodes '(
	("121.199.42.40" 50000)
	("123.207.154.70" 50000)
	("101.201.76.60" 50000)))



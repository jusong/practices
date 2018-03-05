#coding=utf-8

class Dog():
	'''小狗类'''

	def __init__(self, name, age):
		self.name = name
		self.age  = age

	
	def sit(self):
		'''命令小狗坐下'''
		print(self.name.title() + ' is now sitting.')


	def roll_over(self):
		'''命令小狗翻滚'''
		print(self.name.title() + ' rooled over!')


my_dog = Dog('heiqiu', 5)
print('My dog\'s name is ' + my_dog.name.title() + '.')
print('My dog is ' + str(my_dog.age) + ' years old.')

def build_profile(first, last, **user_info):
	profile = {}
	profile['first'] = first
	profile['last'] = last 
	for key, value in user_info.items():
		profile[key] = value
	return profile

user_profile = build_profile('jia', 'fangdong', age=26, height=177, weigth=63)
print(user_profile)

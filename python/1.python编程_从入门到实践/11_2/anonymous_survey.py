#coding:utf-8

class AnonymousSurvey():
    """收集匿名调查问卷答案"""

    def __init__(self, question):
        """存储一个问题"""
        self.question = question
        self.responses = []

    def show_question(self):
        """显示调查问卷"""
        print(self.question)

    def store_response(self, new_response):
        """存储单分调查答案"""
        self.responses.append(new_response)

    def show_results(self):
        print("Survey results:")
        for response in self.responses:
            print("- " + response)

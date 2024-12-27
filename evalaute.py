import json
import numpy as np
from collections import Counter
import math
import os
import nltk
from nltk.tag import pos_tag
from nltk.stem import WordNetLemmatizer
from nltk.tokenize import PunktSentenceTokenizer
from nltk.corpus import wordnet, stopwords
from nltk import sent_tokenize, word_tokenize, PorterStemmer
import pandas as pd

import sys

nltk.download('punkt')
nltk.download('wordnet')
nltk.download('stopwords')
nltk.download('punkt_tab')


ps = PorterStemmer()
lemmatizer = WordNetLemmatizer()
stop_words = stopwords.words('english')
special = ['.', ',', '\'', '"', '-', '/', '*', '+', '=', '!',
           '@', '$', '%', '^', '&', '``', '\'\'', 'We', 'The', 'This']


def normalise(word):
    word = word.lower()
    word = ps.stem(word)
    return word


def get_cosine(vec1, vec2):
    intersection = set(vec1) & set(vec2.keys())
    numerator = sum([vec1[x] * vec2[x] for x in intersection])

    sum1 = sum([vec1[x]**2 for x in vec1.keys()])
    sum2 = sum([vec2[x]**2 for x in vec2.keys()])
    denominator = math.sqrt(sum1) * math.sqrt(sum2)

    if not denominator:
        return 0.0
    else:
        return numerator / denominator


def text_to_vector(text):
    words = word_tokenize(text)
    vec = []
    for word in words:
        if (word not in stop_words):
            if (word not in special):
                w = normalise(word)
                vec.append(w)
    # print Counter(vec)
    return Counter(vec)


def docu_to_vector(sent):
    vec = []
    for text in sent:
        words = word_tokenize(text)
        for word in words:
            if (word not in stop_words):
                if (word not in special):
                    w = normalise(word)
                    vec.append(w)
    # print Counter(vec)
    return Counter(vec)


def f_s_to_s(orig_answer, N, sent):
    cosine_mat = np.zeros(N+1)

    row = 0
    for text in orig_answer:
        maxi = 0
        vector1 = text_to_vector(text)

        for text1 in sent:
            vector2 = text_to_vector(text1)
            cosine = get_cosine(vector1, vector2)

            if (maxi < cosine):
                maxi = cosine

        cosine_mat[row] = maxi

        row += 1

    return cosine_mat

def get_score(orig_ans, stud_ans, max_mark):
    
    
    model_answer = sent_tokenize(orig_ans)

    test_answer = sent_tokenize(stud_ans)

    N = len(model_answer)
    mat = f_s_to_s(model_answer, N, test_answer)

    cnt = docu_to_vector(test_answer)
    cnt = cnt.most_common(10)

    if len(cnt) == 0:
        score = 0
    else:
        thematic = []
        for i in range(len(cnt)):
            thematic.append(cnt[i][0])

        tot = 0

        thematic = ",".join(str(x) for x in thematic)
        thematic = text_to_vector(thematic)

        for i in test_answer:
            i = text_to_vector(i)
            tot = tot + get_cosine(thematic, i)

        point = sum(mat)
        score = point * max_mark * 3/(4*N)
        score = round(min(score + (tot/1.5), max_mark), 2)

        return score


def evaluate():

    
    scores = []

    max_mark = 10
    
    
    df = pd.read_csv(sys.argv[1])
    
    # Student's answer and faculty's expected answer for question 1
    stu_ans1 = df.iloc[0]["student1"]
    unique_words = set(stu_ans1.split())
    stu_ans1 = ' '.join(unique_words)
    exp_ans1 = df.iloc[0]["expected1"]
    
    # Student's answer and faculty's expected answer for question 
    stu_ans2 = df.iloc[0]["student2"]
    unique_words = set(stu_ans2.split())
    stu_ans2 = ' '.join(unique_words)
    exp_ans2 = df.iloc[0]["expected2"]
    
    # Student's answer and faculty's expected answer for question 1
    stu_ans3 = df.iloc[0]["student3"]
    unique_words = set(stu_ans3.split())
    stu_ans3 = ' '.join(unique_words)
    exp_ans3 = df.iloc[0]["expected3"]
    
    # Evaluate answers and get marks scored by students
    question1_marks = round(get_score(exp_ans1, stu_ans1,max_mark), 2)
    question2_marks = round(get_score(exp_ans2, stu_ans2,max_mark), 2)
    question3_marks = round(get_score(exp_ans3, stu_ans3,max_mark), 2)
    
    # Total marks
    total = question1_marks + question2_marks + question3_marks
    if total < 0:
        total = 0
    # Return marks scored to application
    
    
    
    return total

   

print("marrk:")
print(evaluate())

    

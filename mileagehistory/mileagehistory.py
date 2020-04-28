#!C:\Python34\python.exe
#-*- coding: utf-8 -*-
import sys
import codecs
sys.stdout = codecs.getwriter("utf-8")(sys.stdout.detach())
######### 2~5번행 까지는 브라우저에서 한글을 표기하기 위한 코드##########
import cgi
import cgitb
cgitb.enable()
# cgitb는 CGI 프로그래밍시 디버깅을 위한 모듈로, cgitb.enable()
# 할 경우 런타임 에러를 웹브라우저로 전송한다
# cgitb.enable() 하지 않은 상태로 실행 중 오류가 발생한 경우
# 웹서버는 클라이언트에게 HTTP 응답코드 500을 전송한다
print("Content-type: text/html;charset=utf-8\r\n")
# HTTP 규격에서, 헤더 전송 이후에는 반드시 줄바꿈을 하게 되어있으므로 마지막에 \r\n을 전송한다
# 마지막에 \r\n을 전송하지 않으면 브라우저 측에서 오류가 발생한다
 
#############아래부터 파이썬 코드################
import pymysql
 
# MySQL Connection 연결
conn = pymysql.connect(host='localhost', port=3939, user='root', password='jshs1999',
                       db='db', charset='utf8')
 
# Connection 으로부터 Cursor 생성
curs = conn.cursor()

import openpyxl
 
# 엑셀파일 열기
wb = openpyxl.load_workbook('mileagehistory.xlsx')
 
# 현재 Active Sheet 얻기
ws = wb.get_sheet_by_name("Sheet1")
# ws = wb.get_sheet_by_name("Sheet1")
k=1
sql="SELECT * FROM mileagehistory;"
curs.execute(sql)
rows = curs.fetchall()
count=1

for row in rows:
    sql="SELECT ID,Number FROM user WHERE SID={}".format(row[0])
    curs.execute(sql)
    userdata=curs.fetchall()

    chackdate=row[1]
    name=userdata[0]
    number=str(userdata[1])
    isminus=row[3]
    miltype=row[4]
    sayou=row[5]
    if isminus:
        point=['벌점',row[7]]
    else:
        point=['상점',row[6]]
    teachername=row[8]

    data=[count,chackdate,number[0],number[1],number[2:],name,point[0],miltype,sayou,point[1],teachername]
    for i in range(len(data)):
        ws[str(chr(65+i))+"{}".format(count+1)] = data[i]
    count+=1

print("")
# 엑셀 파일 저장
wb.save("mileagehistory2.xlsx")
wb.close()

# Connection 닫기
conn.close()

print("""
이 엑셀은 무료로 해줍니다 10KB이내에 있는 엑셀파일 무료로 받기
<link href="lib/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />
<div class="btn btn-success" onclick="location.href='mileagehistory2.xlsx';">
    <span class="glyphicon glyphicon-download-alt" style="margin-right: 5px;"></span>
    무료 다운로드
</div>
""")

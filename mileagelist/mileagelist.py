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
wb = openpyxl.load_workbook('mileagelist.xlsx')
 
# 현재 Active Sheet 얻기
ws = wb.get_sheet_by_name("Sheet1")
# ws = wb.get_sheet_by_name("Sheet1")
k=1
sql="SELECT * FROM totalmileage;"
curs.execute(sql)
rows = curs.fetchall()

for row in rows:
    SID=row[0]
    Number=row[1]
    ID=row[2]
    plus=row[3]
    minus=row[4]
    data=[str(Number)[0], str(Number)[1], int(str(Number[2:])), ID, plus, minus, plus+minus]    #학년 반 번호 이름 상점 벌점 총점
    for i in range(7):
        ws[str(chr(65+i))+"{}".format(SID-98)] = data[i]


print("")
# 엑셀 파일 저장
wb.save("mileagelist2.xlsx")
wb.close()


# Connection 닫기
conn.close()

print("""
epic mileage list guy
<link href="lib/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />
<div class="btn btn-success" onclick="location.href='checklist2.xlsx';">
    <span class="glyphicon glyphicon-download-alt" style="margin-right: 5px;"></span>
    다운로드
</div>
""")
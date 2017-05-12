select ft.category,ft.titlename,a1.titleid,
									(a1.sum_month+a2.sum_month+a3.sum_month+a4.sum_month) sum_month,
									a1.sum_month a1_sum_month,
									a2.sum_month a2_sum_month,
									a3.sum_month a3_sum_month,
									a4.sum_month a4_sum_month,

									(a1.day25+a2.day25+a3.day25+a4.day25) sum_yestoday,
									a1.day25 a1_yestoday,
									a2.day25 a2_yestoday,
									a3.day25 a3_yestoday,
									a4.day25 a4_yestoday,

									(a1.day0+a2.day0+a3.day0+a4.day0) sum_day0,
									a1.day0 a1_day0,
									a2.day0 a2_day0,
									a3.day0 a3_day0,
									a4.day0 a4_day0,

									(a1.day99+a2.day99+a3.day99+a4.day99) sum_day99,
									a1.day99 a1_day99,
									a2.day99 a2_day99,
									a3.day99 a3_day99,
									a4.day99 a4_day99,

									(y1.s+y2.s+y3.s+y4.s) sum_y,
									y1.s y1,
									y2.s y2,
									y3.s y3,
									y4.s y4

from (select titleid,sum_month,day0,day99,day25 from fundsdata where yearmonth='2017-04' and subcompany=1) a1, -- 每月的 月结、计划数、去年同期、昨天
							(select titleid,sum_month,day0,day99,day25 from fundsdata where yearmonth='2017-04' and subcompany=2) a2,
							(select titleid,sum_month,day0,day99,day25 from fundsdata where yearmonth='2017-04' and subcompany=3) a3,
							(select titleid,sum_month,day0,day99,day25 from fundsdata where yearmonth='2017-04' and subcompany=4) a4,
							(select titleid,sum(sum_month) s from fundsdata where substring(yearmonth,1,4)='2017' and subcompany=1 group by titleid) y1, -- 年合计
							(select titleid,sum(sum_month) s from fundsdata where substring(yearmonth,1,4)='2017' and subcompany=2 group by titleid) y2,
							(select titleid,sum(sum_month) s from fundsdata where substring(yearmonth,1,4)='2017' and subcompany=3 group by titleid) y3,
							(select titleid,sum(sum_month) s from fundsdata where substring(yearmonth,1,4)='2017' and subcompany=4 group by titleid) y4,
			fundstitle ft
where a1.titleid=a2.titleid 
	and a2.titleid=a3.titleid 
	and a3.titleid=a4.titleid 
	and a1.titleid=ft.id 
	and y1.titleid=a1.titleid
	and y2.titleid=a1.titleid
	and y3.titleid=a1.titleid
	and y4.titleid=a1.titleid
order by ft.showorder;

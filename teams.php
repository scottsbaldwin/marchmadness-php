<?php
// http://cbs.sportsline.com/collegebasketball/mayhem/brackets/viewable_men
// http://sports.espn.go.com/ncb/ncaatourney07/bracket
// http://sports.espn.go.com/ncb/ncaatourney06/bracket
// http://sports.espn.go.com/ncb/clubhouse?event=tourney&teamId=150
// "Record:</span> 30-3"
#	Seed	Record	Team ID	Team Abbr
$tabTeams2006 = "
1	Duke	30-3	150
16	Southern U.	20-12	2582
8	George Washington	26-2	45
9	NC-Wilmington	25-7	350
5	Syracuse	23-11	183
12	Texas A&amp;M	21-8	245
4	LSU	23-8	99
13	Iona	23-7	314
6	West Virginia	20-10	277
11	S. Illinois	22-10	79
3	Iowa	25-8	2294
14	Northwestern St	25-7	2466
7	California	20-10	25
10	NC State	21-9	152
2	Texas	27-6	251
15	Penn	20-8	219

1	Memphis	30-3	235
16	Oral Roberts	21-11	198
8	Arkansas	22-9	8
9	Bucknell	26-4	2083
5	Pittsburgh	24-7	221
12	Kent St	25-8	2309
4	Kansas	25-7	2305
13	Bradley	20-10	71
6	Indiana	18-11	84
11	San Diego St	24-8	21
3	Gonzaga	27-3	2250
14	Xavier	21-10	2752
7	Marquette	20-10	269
10	Alabama	17-12	333
2	UCLA	27-6	26
15	Belmont	20-10	2057

1	Connecticut	27-3	41
16	Albany	21-10	399
8	Kentucky	21-12	96
9	UAB	24-6	5
5	Washington	24-6	264
12	Utah St	23-8	328
4	Illinois	25-6	356
13	Air Force	24-6	2005
6	Michigan St	22-11	127
11	George Mason	23-7	2244
3	North Carolina	22-7	153
14	Murray St	24-6	93
7	Wichita St	24-8	2724
10	Seton Hall	18-11	2550
2	Tennessee	21-7	2633
15	Winthrop	23-7	2737

1	Villanova	25-4	222
16	???	???	???
8	Arizona	19-12	12
9	Wisconsin	19-11	275
5	Nevada	27-5	2440
12	Montana St	23-6	149
4	Boston College	26-7	103
13	Pacific	24-7	279
6	Oklahoma	20-8	201
11	Wisc. Milw.	21-8	270
3	Florida	27-6	57
14	South Ala.	24-6	6
7	Georgetown	21-9	46
10	Northern Iowa	23-9	2460
2	Ohio St	25-5	194
15	Davidson	20-10	2166

";

// 2007 tourney
$tabTeams2007 = "
1	Florida	29-5	57
16	Jackson St	9-21	2296
8	Arizona	20-10	12
9	Purdue	21-11	2509
5	Butler	27-6	2086
12	Old Dominion	24-8	295
4	Maryland	24-8	120
13	Davidson	29-4	2166
6	Notre Dame	24-7	87
11	Winthrop	28-4	2737
3	Oregon	26-7	2483
14	Miami (Ohio)	18-14	193
7	UNLV	28-6	2439
10	Georgia Tech	20-11	59
2	Wisconsin	29-5	275
15	Tex A&amp;M CC	25-6	357

1	Kansas	30-4	2305
16	???	???	???
8	Kentucky	21-11	96
9	Villanova	22-10	222
5	Virginia Tech	21-11	259
12	Illinois	23-11	356
4	S Illinois	27-6	79
13	Holy Cross	25-8	107
6	Duke	22-10	150
11	VCU	27-6	2670
3	Pittsburgh	27-7	221
14	Wright St	23-9	2750
7	Indiana	20-9	84
10	Gonzaga	23-10	2250
2	UCLA	26-5	26
15	Weber St	20-11	2692

1	North Carolina	28-6	153
16	E Kentucky	21-11	2198
8	Marquette	24-9	269
9	Michigan St	22-11	127
5	USC	23-11	30
12	Arkansas	21-13	8
4	Texas	24-9	251
13	New Mexico St	25-8	166
6	Vanderbilt	20-11	238
11	George Washington	23-8	45
3	Washington St	25-6	265
14	Oral Roberts	23-10	198
7	Boston College	20-11	103
10	Texas Tech	21-12	2641
2	Georgetown	26-6	46
15	Belmont	23-9	2057

1	Ohio St	30-3	194
16	C Conn St	22-11	2115
8	BYU	25-8	252
9	Xavier	24-8	2752
5	Tennessee	22-10	2633
12	L Beach St	24-7	299
4	Virginia	20-10	258
13	Albany	23-9	399
6	Louisville	23-8	97
11	Stanford	18-12	24
3	Texas A&amp;M	25-6	245
14	Penn	22-8	219
7	Nevada	28-4	2440
10	Creighton	22-10	156
2	Memphis	30-3	235
15	N Texas	23-10	249

";

// 2008 tourney
$tabTeams2008 = "
1	North Carolina	32-2	153	NC
16	???	???	???	??
8	Indiana	25-7	84	IN
9	Arkansas	22-11	8	AR
5	Notre Dame	24-7	87	ND
12	George Mason	23-10	2244	GEOMAS
4	Washington St	24-8	265	WAST
13	Winthrop	22-11	2737	WINTHR
6	Oklahoma	22-11	201	OK
11	St Josephs	21-12	2603	STJOS
3	Louisville	24-8	97	LOU
14	Boise St	25-8	68	BST
7	Butler	29-3	2086	BUT
10	S Alabama	26-6	6	SALAB
2	Tennessee	29-4	2633	TN
15	American	21-11	44	AMER

1	Kansas	31-3	2305	KS
16	Portland St	23-9	2502	PORTST
8	UNLV	26-7	2439	UNLV
9	Kent St	28-6	2309	KENT
5	Clemson	24-9	228	CLEM
12	Villanova	20-12	222	NOVA
4	Vanderbilt	26-7	238	VANDY
13	Siena	22-10	2561	SIE
6	USC	21-11	30	USC
11	Kansas St	20-11	2306	KSST
3	Wisconsin	29-4	275	WI
14	CS Fullerton	24-8	2239	FULL
7	Gonzaga	25-7	2250	GONZAG
10	Davidson	26-6	2166	DAVID
2	Georgetown	27-5	46	GTOWN
15	UMBC	24-8	2378	MDBAL

1	Memphis	33-1	235	MEM
16	UT Arlington	21-11	250	TXARL
8	Mississippi St	22-10	344	MSST
9	Oregon	18-13	2483	OR
5	Michigan St	25-8	127	MIST
12	Temple	21-12	218	TEMP
4	Pittsburgh	26-9	221	PITT
13	Oral Roberts	24-8	198	ORAL
6	Marquette	24-9	269	MARQET
11	Kentucky	18-12	96	KY
3	Stanford	26-7	24	STAN
14	Cornell	22-5	172	CORN
7	Miami (Fla.)	22-10	2390	MIA
10	St Marys (Ca.)	25-6	2608	MARYCA
2	Texas	28-6	251	TX
15	Austin Peay	24-10	2046	AP

1	UCLA	31-3	26	UCLA
16	Mississippi Val	17-15	2400	MSVALST
8	BYU	27-7	252	BYU
9	Texas A&amp;M	24-10	245	TXAM
5	Drake	28-4	2181	DRA
12	W Kentucky	27-6	98	WKTY
4	Connecticut	24-8	41	CT
13	San Diego	21-13	301	SDG
6	Purdue	24-8	2509	PUR
11	Baylor	21-10	239	BAY
3	Xavier	27-6	2752	XAVIER
14	Georgia	17-16	61	GA
7	West Virginia	24-10	277	WV
10	Arizona	19-14	12	AZ
2	Duke	27-5	150	DUKE
15	Belmont	25-8	2057	BEL

";

// 2009 tourney
$tabTeams2009 = "
1	Louisville	28-5	97	LOU
16	???	???	???	??
8	Ohio St	22-10	194	OHST
9	Siena	26-7	2561	SIE
5	Utah	24-9	254	UT
12	Arizona	19-13	12	AZ
4	Wake Forest	24-6	154	WF
13	Cleveland St	25-10	325	CLEVST
6	West Virginia	23-11	277	WV
11	Dayton	26-7	2168	DAY
3	Kansas	25-7	2305	KS
14	N Dakota St	26-6	2449	NDS
7	Boston College	22-11	103	BC
10	USC	21-12	30	USC
2	Michigan St	26-6	127	MIST
15	Robert Morris	24-10	2523	ROB

1	Connecticut	27-4	41	CT
16	Chattanooga	18-16	236	TNCHAT
8	BYU	25-7	252	BYU
9	Texas A&amp;M	23-9	245	TXAM
5	Purdue	25-9	2509	PUR
12	Northern Iowa	23-10	2460	NIA
4	Washington	25-8	264	WA
13	Mississippi St	23-12	344	MSST
6	Marquette	24-9	269	MARQET
11	Utah St	30-4	328	UTST
3	Missouri	28-6	142	MO
14	Cornell	21-9	172	CORN
7	California	22-10	25	CA
10	Maryland	20-13	120	MD
2	Memphis	31-3	235	MEM
15	CS Northridge	17-13	2463	CSNTH

1	Pittsburgh	28-4	221	PITT
16	E Tenn St	23-10	2193	ETNST
8	Oklahoma St	22-11	197	OKST
9	Tennessee	21-12	2633	TN
5	Florida St	25-9	52	FLST
12	Wisconsin	19-12	275	WI
4	Xavier	25-7	2752	XAVIER
13	Portland St	23-9	2502	PORTST
6	UCLA	25-8	26	UCLA
11	VCU	24-9	2670	VACOMM
3	Villanova	26-7	222	NOVA
14	American	24-7	44	AMER
7	Texas	22-11	251	TX
10	Minnesota	22-10	135	MN
2	Duke	28-6	150	DUKE
15	Binghampton	23-8	2066	BING

1	North Carolina	28-4	153	NC
16	Radford	21-11	2515	RADFRD
8	LSU	26-7	99	LSU
9	Butler	26-5	2086	BUT
5	Illinois	24-9	356	IL
12	W Kentucky	24-8	98	WKTY
4	Gonzaga	26-5	2250	GONZAG
13	Akron	23-12	2006	AKR
6	Arizona St	24-9	9	AZST
11	Temple	22-11	218	TEMP
3	Syracuse	26-9	183	SYR
14	SF Austin	24-7	2617	SFA
7	Clemson	23-8	228	CLEM
10	Michigan	20-13	130	MI
2	Oklahoma	27-5	201	OK
15	Morgan St	23-11	2415	MRGNST

";

// http://espn.go.com/mens-college-basketball/tournament/bracket
// http://www.cbssports.com/collegebasketball/mayhem/brackets/viewable_men?tag=pageContainer;column_3
// http://espn.go.com/ncb/ncaatourney10/clubhouse?team=TENN
// http://espn.go.com/ncb/clubhouse?teamId=2439
// http://a.espncdn.com/i/teamlogos/ncaa/50x50/218.png
$tabTeams2010 = "
1	Kansas	32-2	2305	KU
16	Lehigh	22-10	2329	LEH
8	UNLV	25-8	2439	UNLV
9	N Iowa	28-4	2460	UNI
5	Michigan S.	24-8	127	MSU
12	New Mexico St	22-11	166	NMSU
4	Maryland	23-8	120	MARY
13	Houston	19-15	248	HOU
6	Tennessee	25-8	2633	TENN
11	San Diego St	25-8	21	SDSU
3	Georgetown	23-10	46	GU
14	Ohio	21-14	195	NDS
7	Oklahoma St	22-10	197	OKST
10	Georgia Tech	22-12	59	GT
2	Ohio St	27-7	194	OHST
15	UC Santa Barbara	20-9	2540	?

1	Syracuse	28-4	183	SYR
16	Vermont	25-9	261	?
8	Gonzaga	26-6	2250	GON
9	Florida St	22-9	52	FSU
5	Butler	28-4	2086	BUT
12	UTEP	26-6	2638	UTEP
4	Vanderbilt	24-8	238	VAN
13	Murray St	30-4	93	MSU
6	Xavier	24-8	2752	XAV
11	Minnesota	21-13	135	MINN
3	Pittsburgh	24-8	221	PITT
14	Oakland	26-8	2473	?
7	BYU	29-5	252	BYU
10	Florida	21-12	57	FLA
2	Kansas State	26-7	2306	KSU
15	North Texas	24-8	249	UNT

1	Kentucky	32-2	96	UK
16	E Tennessee St	20-14	2193	ETSU
8	Texas	24-9	251	TEX
9	Wake Forest	19-10	154	WFU
5	Temple	29-5	218	TEM
12	Cornell	27-4	172	COR
4	Wisconsin	23-8	275	WIS
13	Wofford	26-8	2747	WOF
6	Marquette	22-11	269	MARQ
11	Washington	24-9	264	UW
3	New Mexico	29-4	167	UNM
14	Montana	22-9	149	UM
7	Clemson	21-10	228	CLEM
10	Missouri	22-10	142	 MO
2	W Virginia	27-6	277	WVU
15	Morgan St	27-9	2415	MST

1	Duke	29-5	150	DUKE
16	???	?	?	?
8	California	23-10	25	CAL
9	Louisville	20-12	97	UL
5	Texas A&amp;M	23-9	245	TA&M
12	Utah St	27-7	328	USU
4	Purdue	27-5	2509	PU
13	Siena	27-6	2561	SIEN
6	Notre Dame	23-11	87	ND
11	Old Dominion	26-8	295	?
3	Baylor	25-7	239	BU
14	Sam Houston St	25-7	2534	SHSU
7	Richmond	26-8	257	RICH
10	Saint Mary's	26-5	2608	SMC
2	Villanova	24-7	222	VILL
15	Robert Morris	23-11	2523	RMC

";

$tabTeams2011 = "
1	Ohio St	32-2	194	OHST
16	UTSA	20-13	2636	?
8	George Mason	26-6	2244	GEOMAS
9	Villanova	21-11	222	VILL
5	W Virginia	20-11	277	WVU
12	Clemson	22-11	228	?
4	Kentucky	25-8	96	UK
13	Princeton	25-6	163	PRINCE
6	Xavier	24-7	2752	XAV
11	Marquette	20-14	269	MARQ
3	Syracuse	26-7	183	SYR
14	Indiana St	20-13	282	INST
7	Washington	23-10	264	UW
10	Georgia	21-11	61	GA
2	North Carolina	26-7	153	NC
15	LIU	27-5	2341	LIU

1	Duke	30-4	150	DUKE
16	Hampton	24-8	2261	HAMP
8	Michigan	20-13	130	MI
9	Tennessee	19-14	2633	TENN
5	Arizona	27-7	12	AZ
12	Memphis	25-9	235	MEM
4	Texas	27-7	251	TEX
13	Oakland	25-9	2473	OAK
6	Cincinnati	25-8	2132	CIN
11	Missouri	23-10	142	 MO
3	Connecticut	26-9	41	
14	Bucknell	25-8	2083	
7	Temple	25-7	218	TEM
10	Penn St	19-14	213	
2	San Diego St	32-2	21	SDSU
15	N Colorado	21-10	2458	

1	Kansas	32-2	2305	KU
16	Boston	21-13	104	
8	UNLV	24-8	2439	UNLV
9	Illinois	19-13	356	
5	Vanderbilt	23-10	238	VAN
12	Richmond	27-7	257	RICH
4	Louisville	25-9	97	UL
13	Morehead St	24-9	2413	
6	Georgetown	21-10	46	GU
11	VCU	24-11	2670	?
3	Purdue	25-7	2509	PU
14	Saint Peters	20-13	2612	
7	Texas A&amp;M	24-8	245	TA&M
10	Florida St	21-10	52	FSU
2	Notre Dame	26-6	87	ND
15	Akron	23-12	2006	

1	Pittsburgh	27-5	221	PITT
16	UNCA	20-13	2427	?
8	Butler	23-9	2086	BUT
9	Old Dominion	27-6	295	?
5	Kansas State	22-10	2306	KSU
12	Utah St	30-3	328	USU
4	Wisconsin	23-8	275	WIS
13	Belmont	30-4	2057	
6	St Johns	21-11	2599	
11	Gonzaga	24-9	2250	GON
3	BYU	30-4	252	BYU
14	Wofford	21-12	2747	WOF
7	UCLA	22-10	26	
10	Michigan S.	19-14	127	MSU
2	Florida	26-7	57	FLA
15	UC Santa Barbara	18-13	2540	?

";

$tabTeams2012 = "
1	Kentucky	32-2	96	UK
16	W Kentucky	16-18	98	WKTY
8	Iowa St	22-10	66	?
9	Connecticut	20-13	41	CT
5	Wichita St	27-5	2724	?
12	VCU	28-6	2670	VCU
4	Indiana	25-8	84	IN
13	New Mexico St	26-9	166	NMSU
6	UNLV	26-8	2439	UNLV
11	Colorado	23-11	38	?
3	Baylor	27-7	239	BU
14	S Dakota St	27-7	2571	SDST
7	Notre Dame	22-11	87	ND
10	Xavier	21-12	2752	XAV
2	Duke	27-6	150	DUKE
15	Lehigh	26-7	2329	LEH

1	Michigan St	27-7	127	MIST
16	LIU	25-8	2341	LIU
8	Memphis	26-8	235	MEM
9	St Louis	25-7	139	?
5	New Mexico	27-6	167	UNM
12	Long Beach St	25-8	299	LBSU
4	Louisville	26-9	97	UL
13	Davidson	25-7	2166	?
6	Murray St	30-1	93	MSU
11	Colorado St	20-11	36	CSU
3	Marquette	25-7	269	MARQ
14	BYU	26-8	252	BYU
7	Florida	23-10	57	FLA
10	Virginia	22-9	258	UVA
2	Missouri	30-4	142	MO
15	Norfolk St	25-9	2450	?

1	Syracuse	31-2	183	SYR
16	UNC Asheville	24-9	2427	?
8	Kansas State	21-10	2306	KSU
9	Southern Miss	25-8	2572	?
5	Vanderbilt	24-10	238	VAN
12	Harvard	26-4	108	?
4	Wisconsin	24-9	275	WIS
13	Montana	25-6	149	UM
6	Cincinnati	24-10	2132	CIN
11	Texas	20-13	251	TEX
3	Florida St	24-9	52	FSU
14	St Bonaventure	20-11	179	?
7	Gonzaga	25-6	2250	GON
10	West Virginia	19-13	277	WV
2	Ohio St	27-7	194	OHST
15	Loyola (Md)	24-8	2352	?

1	North Carolina	29-5	153	UNC
16	Vermont	24-11	261	?
8	Creighton	28-5	156	CREI
9	Alabama	21-11	333	ALA
5	Temple	24-7	218	TEMP
12	S Florida	21-13	58	?
4	Michigan	24-9	130	MI
13	Ohio	27-7	195	OHIO
6	San Diego St	26-7	21	SDSU
11	NC State	22-12	152	NCST
3	Georgetown	23-8	46	GTOWN
14	Belmont	27-7	2057	BEL
7	St Marys (Ca.)	27-5	2608	MARYCA
10	Purdue	21-12	2509	PUR
2	Kansas	27-6	2305	KU
15	Detroit	22-13	2174	DET

";

$tabTeams2013 = "
1	Florida	32-2	57	FLA
16	?		?	?
8	Colorado St	25-8	36	CSU
9	Missouri	23-10	142	MO
5	Oklahoma St	24-8	197	OKST
12	Oregon	26-8	2483	OR
4	St Louis	27-6	139	STLOU
13	New Mexico St	24-10	166	NMSU
6	Memphis	30-4	235	MEM
11	?		?	?
3	Michigan St	25-8	127	MIST
14	Valparaiso	26-7	2674	VALPO
7	Creighton	27-7	156	CREI
10	Cincinnati	22-11	2132	CIN
2	Duke	27-5	150	DUKE
15	Albany	24-10	399	ALBANY

1	Gonzaga	31-2	2250	GONZAG
16	Southern U.	23-9	2582	STHRN
8	Pittsburgh	24-8	221	PITT
9	Wichita St	26-8	2724	WICHST
5	Wisconsin	23-11	275	WIS
12	Ole Miss	26-8	145	MISS
4	Kansas State	27-7	2306	KSU
13	?		?	?
6	Arizona	25-7	12	AZ
11	Belmont	26-6	2057	BEL
3	New Mexico	29-5	167	UNM
14	Harvard	19-9	108	HARV
7	Notre Dame	25-9	87	ND
10	Iowa St	22-11	66	IOWAST
2	Ohio St	26-7	194	OHST
15	Iona	20-13	314	IONA

1	Kansas	29-5	2305	KS
16	W Kentucky	20-15	98	WKTY
8	North Carolina	24-10	153	UNC
9	Villanova	20-13	222	NOVA
5	VCU	26-8	2670	VACOMM
12	Akron	26-6	2006	AKR
4	Michigan	20-12	130	MI
13	S Dakota St	25-9	2571	SDST
6	UCLA	25-9	26	UCLA
11	Minnesota	20-12	135	MN
3	Florida	26-7	57	FLA
14	Northwestern St	23-8	2466	NWST
7	San Diego St	22-10	21	SDSU
10	Oklahoma	20-11	201	OK
2	Georgetown	25-6	46	GTOWN
15	FGCU	24-10	526	FGC

1	Indiana	27-6	84	IN
16	?		?	?
8	NC State	24-10	152	NCST
9	Temple	23-9	218	TEMP
5	UNLV	25-9	2439	UNLV
12	California	20-11	25	CA
4	Syracuse	26-9	183	SYR
13	Montana	25-6	149	UM
6	Butler	26-8	2086	BUT
11	Bucknell	28-5	2083	BUCK
3	Marquette	23-8	269	MARQ
14	Davidson	26-7	2166	DAVID
7	Illinois	22-12	356	IL
10	Colorado	21-11	38	COLO
2	Miami (Fla.)	27-6	2390	MIA
15	Pacific	22-12	279	UOP

";

// http://espn.go.com/ncb/clubhouse?teamId=2439
// http://espn.go.com/mens-college-basketball/tournament/bracket
$tabTeams2014 = "
1	Florida	32-2	57	FLA
16	Albany	18-14	399	ALBANY
8	Colorado	23-11	38	COLO
9	Pittsburgh	25-9	221	PITT
5	VCU	26-8	2670	VACOMM
12	SF Austin	31-2	2617	SFA
4	UCLA	26-8	26	UCLA
13	Tulsa	21-12	202	TULSA
6	Ohio St	25-9	194	OHIOST
11	Dayton	23-10	2168	DAYTON
3	Syracuse	27-5	183	CUSE
14	W Michigan	23-9	2711	WMICH
7	New Mexico	27-6	167	NMEX
10	Stanford	21-12	24	STNFRD
2	Kansas	24-9	2305	KANSAS
15	Eastern Ky	24-9	2198	EKY

1	Virginia	28-6	258	UVA
16	Coast Car	21-12	324	CSTCAR
8	Memphis	23-9	235	MEMP
9	Geo Wash	24-8	45	GWASH
5	Cincinnati	27-6	2132	CINCY
12	Harvard	26-4	108	HARV
4	Michigan St	26-8	127	MICHST
13	Delaware	25-9	48	DEL
6	N Carolina	23-9	153	UNC
11	Providence	23-11	2507	PROV
3	Iowa St	26-7	66	IOWAST
14	NC Central	28-5	2428	NCCU
7	Connecticut	26-8	41	UCONN
10	Saint Joes	24-9	2603	IOWAST
2	Villanova	28-4	222	NOVA
15	Milwuakee	21-13	270	MILW

1	Arizona	30-4	12	ARIZ
16	Weber St	19-11	2692	WEBER
8	Gonzaga	28-6	2250	GONZAG
9	Oklahoma St	21-12	197	OKLAST
5	Oklahoma	23-9	201	OKLA
12	N Dakota St	25-6	2449	NDAKST
4	San Diego St	29-4	21	SDGST
13	New Mexico St	26-9	166	NMEXST
6	Baylor	24-11	239	BAYLOR
11	Nebraska	19-12	158	NEB
3	Creighton	26-7	156	CREIGH
14	UL Lafayette	23-11	309	LALAF
7	Oregon	23-9	2483	OREG
10	BYU	23-11	252	BYU
2	Wisconsin	2607	275	WISC
15	American	20-12	44	AMER

1	Wichita St	34-0	2724	WICHST
16	Cal Poly	13-19	13	CPOLY
8	Kentucky	24-10	96	UK
9	Kansas St	20-12	2306	KSTATE
5	St Louis	26-6	139	STLOU
12	N.C. State	21-13	152	NCST
4	Louisville	29-5	97	LVILLE
13	Manhattan	25-7	2363	MANH
6	UMass	24-8	113	UMASS
11	?	?	?	?
3	Duke	26-8	150	DUKE
14	Mercer	26-8	2382	MERCER
7	Texas	23-10	251	TEXAS
10	Arizona St	21-11	9	ARIZST
2	Michigan	25-8	130	MICH
15	Wofford	20-12	2747	WOFF

";
$tabTeams = $tabTeams2014;

class Bracketology {
	var $bracketTeams = array();

	function findTeam($teamName) {
		$match = null;
		foreach ($this->bracketTeams as $team) {
			if ($team->teamName == $teamName) {
				$match = $team;
			}
		}
		return $match;
	}

	function addTeam($bracketTeam) {
		array_push($this->bracketTeams, $bracketTeam);
	}
}

class BracketTeam {
	var $teamId;
	var $seed;
	var $teamName;
	var $record;
	var $teamAbbreviation;
	function BracketTeam() { }
}



$lines = explode("\n", $tabTeams);
$GLOBALS['teamArray'] = array();
$b = new Bracketology();

foreach ($lines as $l) {
	$parts = explode("\t", $l);
	if (count($parts) >= 4) {
		$t = new BracketTeam();
		$t->seed = $parts[0];
		$t->teamName = $parts[1];
		$t->record = $parts[2];
		$t->teamId = $parts[3];
		$t->teamAbbreviation = $parts[4];
		//array_push($GLOBALS['teamArray'], $t);
		$b->addTeam($t);
		
		/*
		// get the record
		$html = implode("", file("http://sports.espn.go.com/ncb/clubhouse?event=tourney&teamId=".$t->teamId));
		$pattern = "|Record:</span> (\d+-\d+)|";
		$matches = array();
		preg_match($pattern, $html, $matches);
		if (count($matches) > 0 && $matches[1]) {
			$t->record = $matches[1];
		} else {
			$t->record = "???";
		}
		printf("%s\t%s\t%s\t%s\n", $t->seed, $t->teamName, $t->record, $t->teamId);
		*/
	} else {
		continue;
	}
}
$GLOBALS['bracketology'] = $b;

?>

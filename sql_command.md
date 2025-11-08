###  HEADER ใบเสร็จรับเงินตามวันที่
```
SELECT * FROM receipth WHERE recpdate = '25680905'
```
###  DETAIL ใบเสร็จรับเงินตามเลขที่ใบเสร็จ
```
SELECT receiptd.*, cc.chr_des FROM receiptd 
LEFT JOIN chr_code cc ON receiptd.chr_code = cc.chr_code
WHERE recphid = (SELECT recphid FROM receipth WHERE refer IN ('1102867-1102868'))
```

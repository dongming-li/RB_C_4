SELECT FILES.id FROM FILES
INNER JOIN BRANCHES
ON FILES.branch_id = BRANCHES.id 
WHERE BRANCHES.id = '1'
AND FILES.path = ''
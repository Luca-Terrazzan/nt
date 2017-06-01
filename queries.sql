SELECT * FROM node_tree;
SELECT * FROM node_tree_names;

SELECT Child.idNode, Child.iLeft, Child.iRight, Child.level
FROM node_tree as Child, node_tree as Parent
WHERE
	Child.level = Parent.level + 1
	AND Child.iLeft > Parent.iLeft
	AND Child.iRight < Parent.iRight
    AND Parent.level = 2
	AND Parent.iLeft = 12
    AND Parent.iRight = 19

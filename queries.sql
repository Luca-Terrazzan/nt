SELECT * FROM node_tree;
SELECT * FROM node_tree_names;

-- direct children
SELECT Child.idNode, Child.iLeft, Child.iRight, Child.level
FROM node_tree AS Child, node_tree AS Parent
WHERE
	Child.level = Parent.level + 1
	AND Child.iLeft > Parent.iLeft
	AND Child.iRight < Parent.iRight
    AND Parent.level = 2
	AND Parent.iLeft = 12
    AND Parent.iRight = 19

--  all children
SELECT Child.idNode, Child.iLeft, Child.iRight, Child.level
FROM node_tree AS Child, node_tree AS Parent
WHERE
	Child.iLeft > Parent.iLeft
	AND Child.iRight < Parent.iRight
	AND Parent.iLeft = 12
    AND Parent.iRight = 19

SELECT Child.idNode, Trans.nodeName
FROM node_tree AS Child, node_tree AS Parent, node_tree_names AS Trans
WHERE
	Child.iLeft > Parent.iLeft
	AND Child.iRight < Parent.iRight
	AND Parent.iLeft = 12
    AND Parent.iRight = 19
    AND Trans.idNode = Child.idNode
    AND Trans.language = 'Italian';

<?php

// IMPORTANT : This Class Is Still In Proccess Of Implemeting.
// Notice : DO NOT USE THIS CLASS!
// Script by TadavomnisT

class Node 
{
    public function __construct(array $values = [] , Node $parent = null , array $children = []) {
        $this->values = $values;
        $this->parent = $parent;
        $this->children = $children;
    }
    public function getValues()
    {
        return $this->values;
    }
    public function getParent()
    {
        return $this->parent;
    }
    public function getChildren()
    {
        return $this->children;
    }
    public function addValues( $newValue )
    {
        $this->values[] = $newValue;
        return $this->values;
    }
    public function setParent( $newParent )
    {
        $this->parent = $newParent;
        return $this->parent;
    }
    public function addChildren( $newChild )
    {
        $this->children[] = $newChild;
        return $this->children;
    }
    public function delValues( $index )
    {
        unset($this->values[$index]);
        return $this->values;
    }
    public function delParent()
    {
        $this->parent = null;
        return true;
    }
    public function delChildren( $index )
    {
        unset($this->children[$index]);
        return $this->children;
    }
    public function isLeafe()
    {
        if( count($this->children) === 0 ) return true;
        return false;
    }
}

class B_Plus_Tree 
{
    public function __construct( int $maxDegree , Value $values = null) {
        $this->nodes = [];
        $this->maxDegree = $maxDegree;
        if ($values !== null)
        foreach ($values as $value) {
            insert($value);
        }
    }
    public function insert(Type $var = null)
    {
        if( count($this->nodes) === 0 )
        {

        }
    }
    public function delete(Type $var = null)
    {
        # code...
    }
    public function search(Type $var = null)
    {
        if( count($this->nodes) === 0 ) return false;
        elseif (condition) {
            # code...
        }
        else {
            # code...
        }
    }
    public function print(Type $var = null)
    {
        # code...
    }
    public function getRoot()
    {
        foreach ($this->nodes as $key => $node) {
            if($node->parent === null) return [$key , $node];
        }
        return false;
    }
    public function addNode(array $values = [] , Node $parent = null , array $children = [])
    {
        // if(count ( $values ) > 0 && ( $parent !== null ) && count ( $children ) > 0 )
        $this->nodes[] = new Node ( $values , $parent , $children );
        return [
            (count( $this->nodes ) -1),
            $this->nodes[(count( $this->nodes ) -1)]
        ];
    }
}


?>
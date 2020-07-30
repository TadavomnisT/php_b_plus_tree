<?php

// IMPORTANT : This Class Is Still In Proccess Of Implemeting.
// Notice : DO NOT USE THIS CLASS!
// Script by TadavomnisT

class Node 
{
    public function __construct(array $values , int $parent = null , array $children = []) {        
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
    public function setValues( $newValues = [] )
    {
        $this->values = $newValues;
        asort($this->values);
        return $this->values;
    }
    public function addValues( $newValue )
    {
        $this->values[] = $newValue;
        asort($this->values);
        return $this->values;
    }
    public function setParent( $newParent )
    {
        $this->parent = $newParent;
        return $this->parent;
    }
    public function setChildren( $newChildren = [] )
    {
        $this->children = $newChildren;
        return $this->children;
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
        // $this->root = null;
        $this->maxDegree = $maxDegree;
        if ($values !== null)
        foreach ($values as $value) {
            insert($value);
        }
    }
    public function insert(array $insertValue = [])
    {

        foreach ($insertValue as $value) {
            if( count($this->nodes) === 0 )
            {
                // $this->nodes[0] = new Node([$value]);
                $this->addNode( [$value] );
                // $this->root = 0 ;
            }
            else if ( count($this->nodes) === 1 ) 
            {
                if ( (count($this->nodes[0]->values) + 1) < $this->maxDegree ) {
                    $this->nodes[0]->addValues( $value );
                }
                else 
                {
                    $this->nodes[0]->addValues( $value );
                    //...break node...
                    $breakIndex = floor(count($this->nodes[0]->values) / 2);
                    $leftChild =[];
                    $rightChild =[];
                    foreach ($this->nodes[0]->values as $key => $val) {
                        if($key < $breakIndex) $leftChild[] = $val;
                        else $rightChild[] = $val;
                    }
                    $leftChildIndex = $this->addNode($leftChild , 0)['key'];
                    $rightChildIndex = $this->addNode($rightChild , 0)['key'];
                    $this->nodes[0]->setValues( [$this->nodes[0]->values[$breakIndex]] );
                    $this->nodes[0]->addChildren($leftChildIndex);
                    $this->nodes[0]->addChildren($rightChildIndex);
                }
            }
            else 
            {
                $searchResult = $this->search($value);
            }
        }

        
    }
    public function delete(Type $var = null)
    {
        # code...
    }
    public function search( $value )
    {
        if( count($this->nodes) === 0 ) return false;
        elseif (count($this->nodes) === 1) {
            return [ "key" => array_search($value , $this->getRoot["node"]) , "node" => $this->getRoot ];
        }
        else {
            $tempNode = getRoot()['node'];
            $tempKey = getRoot()['key'];
            do {
                $flag = false;
                foreach ($tempNode->values as $key => $tempValue) {
                    if($value === $tempValue){
                        $tempKey = $tempNode->children[ ($key + 1) ];
                        $tempNode = $this->nodes[$tempKey] ;
                        $flag = true;
                    }
                    if($value < $tempValue) {
                        $tempKey = $tempNode->children[ $key ];
                        $tempNode = $this->nodes[$tempKey] ;
                        $flag = true;
                    }
                }
                if ( !$flag ) {
                    //means it was bigger than all.
                    $tempKey = $tempNode->children[ (count($tempNode->children) - 1) ];
                    $tempNode = $this->nodes[$tempKey] ;
                }
            } while ( !($tempNode->isLeafe()) );
            return [ "key" => array_search($value , $tempNode) , "node" => ["key" => $tempKey , "node" => $tempNode ] ];
        }
    }
    public function convertToArray()
    {
        if( count($this->nodes) === 0 ) return [];
        elseif (count($this->nodes) === 1) {
            return [ getRoot()['node']->values ];
        }
        else {
            $tree = [];
            $tempArray =[];
            foreach ($this->nodes as $key => $node) {
                $depthOfNode = 0;
                while ($node->parrent != null) {
                    $depthOfNode++;
                    $node = $this->nodes[$node->parent];
                }
                $tempArray[$key] = $depthOfNode;
            }
            foreach ($tempArray as $key => $value) {
                $tree[$value][] = $this->nodes[$key]->values;
            }
            return $tree;
        }
        

    }
    public function print()
    {
        print_r($this->convertToArray());
    }
    public function getDepth()
    {
        if( count($this->nodes) === 0 ) return 0;
        $tempNode = getRoot()['node'];
        $depth = 0;
        do {
            $tempNode= $this->nodes[$tempNode->children[0]];
            $depth++;
        }while (!($tempNode->isLeafe()));
        return $depth;
    }
    public function getRoot()
    {
        foreach ($this->nodes as $key => $node) {
            if ($node->parent === null) return ["key" => $key , "node" => $node];
        }
        return false;
    }
    public function addNode(array $values = [] , int $parent = null , array $children = [])
    {
        $this->nodes[] = new Node ( $values , $children );
        return [
            "key" => (count( $this->nodes ) -1),
            "node" => $this->nodes[(count( $this->nodes ) -1)]
        ];
    }
}


?>

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
        ksort($this->values);
        $this->values = array_values($this->values);
        return $this->values;
    }
    public function addValues( $newValue )
    {
        $this->values[] = $newValue;
        asort($this->values);
        ksort($this->values);
        $this->values = array_values($this->values);
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
        ksort($this->children);
        $this->children = array_values($this->children);
        return $this->children;
    }
    public function addChildren( $newChild )
    {
        $this->children[] = $newChild;
        ksort($this->children);
        $this->children = array_values($this->children);
        return $this->children;
    }
    public function delValues( $index )
    {
        unset($this->values[$index]);
        ksort($this->values);
        $this->values = array_values($this->values);
        return $this->values;
    }
    public function delAllValues()
    {
        $this->values = [];
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
        ksort($this->children);
        $this->children = array_values($this->children);
        return $this->children;
    }
    public function delAllChildren()
    {
        $this->children = [];
        return $this->children;
    }
    public function isLeafe()
    {
        if( count($this->children) === 0 ) return true;
        return false;
    }
    public function popFirstValue()
    {
        $return = $this->values[0];
        unset($this->values[0]);
        ksort($this->values);
        $this->values = array_values($this->values);
        return $return;
    }
}

class B_Plus_Tree 
{
    public function __construct( int $maxDegree , array $values = null) {
        $this->nodes = [];
        $this->maxDegree = $maxDegree;
        if ($values !== null) $this->insert($values);
    }
    public function insert(array $insertValue)
    {
        if( !is_array($insertValue) ) $insertValue = [ $insertValue ];
        foreach ($insertValue as $value) {
            if( count($this->nodes) === 0 )
            {
                $this->addNode( [$value] );                
            }
            else if ( count($this->nodes) === 1 ) 
            {
                if ( (count($this->nodes[$this->getRoot()["key"]]->values) + 1) < $this->maxDegree ) {
                    $this->nodes[$this->getRoot()["key"]]->addValues( $value );
                }
                else 
                {   //insert
                    $preRootKey = $this->getRoot()["key"];
                    $this->nodes[$preRootKey]->addValues( $value );
                    //...break node...
                    $breakIndex = floor(count($this->nodes[$preRootKey]->values) / 2);
                    $leftChild =[];
                    $rightChild =[];
                    foreach ($this->nodes[$preRootKey]->values as $key => $val) {
                        if($key < $breakIndex) $leftChild[] = $val;
                        else $rightChild[] = $val;
                    }
                    $newRootKey = $this->addNode( [$rightChild[0]] )['key'];
                    $this->nodes[$preRootKey]->setValues( $leftChild );
                    $this->nodes[$preRootKey]->setParent($newRootKey);
                    $this->nodes[$preRootKey]->delAllChildren();
                    $rightChildIndex = $this->addNode($rightChild , $newRootKey)['key'];
                    $this->nodes[$newRootKey]->setChildren( [$preRootKey , $rightChildIndex] );
                }
            }
            else 
            {
                $searchResult = $this->search($value);
                if ( (count($this->nodes[$searchResult["node"]["key"]]->values) + 1) < $this->maxDegree ) {
                    $this->nodes[$searchResult["node"]["key"]]->addValues( $value );
                }
                else { //**
                    $tempNode = $searchResult["node"]["node"];
                    $tempKey = $searchResult["node"]["key"];
                    // insert
                    $this->nodes[$tempKey]->addValues( $value );
                    // break
                    $breakIndex = floor(count($this->nodes[$tempKey]->values) / 2);
                    $leftChild =[];
                    $rightChild =[];
                    foreach ($this->nodes[$tempKey]->values as $key => $val) {
                        if($key < $breakIndex) $leftChild[] = $val;
                        else $rightChild[] = $val;
                    }
                    $this->nodes[$tempKey]->setValues($leftChild);
                    $rightChildIndex = $this->addNode($rightChild , $this->nodes[$tempKey]->parent)['key'];
                    if ( (count($this->nodes[$this->nodes[$tempKey]->parent]->values) + 1) >= $this->maxDegree ) {//if parent need to be broken
                        while (true) {
                            $childKey =$tempKey;
                            $tempKey = $this->nodes[$tempKey]->parent ;
                            $tempNode = $this->nodes[$tempKey] ;
                            //insert
                            foreach ($this->nodes[$tempKey]->children as $key => $value) {
                                if($value == $childKey ) $theKey = $key;
                            }
                            $this->nodes[$tempKey]->addValues( $rightChild[0] );
                            $tempChildren = $this->nodes[$tempKey]->children;
                            array_splice($tempChildren, ($theKey + 1), 0, array($rightChildIndex));
                            $this->nodes[$tempKey]->setChildren($tempChildren);
                            //breaking----------------------------------
                            if ( $this->nodes[$tempKey]->parent !== null ){
                                $breakIndexValues = floor(count($this->nodes[$tempKey]->values) / 2);
                                $leftChildValues =[];
                                $rightChildValues =[];
                                foreach ($this->nodes[$tempKey]->values as $key => $val) {
                                    if($key < $breakIndexValues) $leftChildValues[] = $val;
                                    else $rightChildValues[] = $val;

                                }
                                $breakIndexChildren = floor(count($this->nodes[$tempKey]->children) / 2);
                                $leftChildChildren =[];
                                $rightChildChildren =[];
                                foreach ($this->nodes[$tempKey]->children as $key => $val) {
                                    if($key < $breakIndexChildren) $leftChildChildren[] = $val;
                                    else $rightChildChildren[] = $val;
                                }
                                $rightChildIndex = $this->addNode([] , $this->nodes[$tempKey]->parent)['key'];
                                $this->nodes[$tempKey]->setChildren($leftChildChildren);
                                $this->nodes[$tempKey]->setValues($leftChildValues);
                                $this->nodes[$rightChildIndex]->setChildren($rightChildChildren);
                                $this->nodes[$rightChildIndex]->setValues($rightChildValues);
                                
                                if(( !($tempNode->isLeafe()) )) $this->nodes[$rightChildIndex]->popFirstValue();
                                foreach ($rightChildChildren as $index) {
                                    $this->nodes[$index]->setParent($rightChildIndex);
                                }
                            }
                            // -----------------------------------------
                            if ( $this->nodes[$tempKey]->parent === null ) { // if parrent was null means tree finished
                                if( count( $this->nodes[$tempKey]->values ) >= $this->maxDegree  )
                                {//make a new root
                                    $breakIndexValues = floor(count($this->nodes[$tempKey]->values) / 2);
                                    $leftChildValues =[];
                                    $rightChildValues =[];
                                    foreach ($this->nodes[$tempKey]->values as $key => $val) {
                                        if($key < $breakIndexValues) $leftChildValues[] = $val;
                                        else $rightChildValues[] = $val;
                                    }
                                    $breakIndexChildren = floor(count($this->nodes[$tempKey]->children) / 2);
                                    $leftChildChildren =[];
                                    $rightChildChildren =[];
                                    foreach ($this->nodes[$tempKey]->children as $key => $val) {
                                        if($key < $breakIndexChildren) $leftChildChildren[] = $val;
                                        else $rightChildChildren[] = $val;
                                    }
                                    $newRootKey = $this->addNode( [$rightChildValues[0]] , null )['key'];
                                    $rightChildIndex = $this->addNode([] , $newRootKey)['key'];
                                    $this->nodes[$newRootKey]->setChildren([ $tempKey  , $rightChildIndex ]);
                                    $this->nodes[$tempKey]->setChildren($leftChildChildren);
                                    $this->nodes[$tempKey]->setValues($leftChildValues);
                                    $this->nodes[$tempKey]->setParent($newRootKey);
                                    $this->nodes[$rightChildIndex]->setChildren($rightChildChildren);
                                    $this->nodes[$rightChildIndex]->setValues($rightChildValues);
                                    if(( !($tempNode->isLeafe()) )) $this->nodes[$rightChildIndex]->popFirstValue();
                                    foreach ($rightChildChildren as $index) {
                                        $this->nodes[$index]->setParent($rightChildIndex);
                                    }
                                }
                                break;
                            }
                            if ( (count($this->nodes[$this->nodes[$tempKey]->parent]->values) + 1) < $this->maxDegree) {
                                foreach ($this->nodes[$this->nodes[$tempKey]->parent]->children as $key => $value) {
                                    if($value === $tempKey ) $theKey = $key;
                                }
                                $this->nodes[$this->nodes[$tempKey]->parent]->addValues( $rightChildValues[0] );
                                $tempChildren = $this->nodes[$this->nodes[$tempKey]->parent]->children;
                                array_splice($tempChildren, ($theKey + 1), 0, array($rightChildIndex));
                                $this->nodes[$this->nodes[$tempKey]->parent]->setChildren($tempChildren);
                                break;
                            }
                        }
                    }
                    else{ //if parrent does't break
                        foreach ($this->nodes[$this->nodes[$tempKey]->parent]->children as $key => $value) {
                            if($value == $tempKey ) $theKey = $key;
                        }
                        $this->nodes[$this->nodes[$tempKey]->parent]->addValues( $rightChild[0] );
                        $tempChildren = $this->nodes[$this->nodes[$tempKey]->parent]->children;
                        array_splice($tempChildren, ($theKey + 1), 0, array($rightChildIndex));
                        $this->nodes[$this->nodes[$tempKey]->parent]->setChildren($tempChildren);
                    }
                }
            }
        }

        
    }
    public function delete(int $value)
    {
        # I should put sth here...
    }
    public function search( $value )
    {
        if( count($this->nodes) === 0 ) return false;
        elseif (count($this->nodes) === 1) {
            return [ "key" => array_search($value , $this->getRoot()["node"]) , "node" => $this->getRoot() ];
        }
        else {
            $tempNode = $this->getRoot()['node'];
            $tempKey = $this->getRoot()['key'];
            do {
                $flag = false;
                foreach ($tempNode->values as $key => $tempValue) {
                    if (!($tempNode->isLeafe())) break ;
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
                    $tempKey = $tempNode->children[ (count($tempNode->children) - 1) ];
                    $tempNode = $this->nodes[$tempKey] ;
                }
            } while ( !($tempNode->isLeafe()) );
            return [ "key" => array_search($value , $tempNode->values) , "node" => ["key" => $tempKey , "node" => $tempNode ] ];
        }
    }
    public function convertToArray()
    {
        if( count($this->nodes) === 0 ) return [];
        elseif (count($this->nodes) === 1) {
            return [ $this->getRoot()['node']->values ];
        }
        else {
            $tree = [];
            $tempArray =[];
            foreach ($this->nodes as $key => $node) {
                $depthOfNode = 0;
                while ($node->parent != null) {
                    $depthOfNode++;
                    $node = $this->nodes[$node->parent];
                }
                $tempArray[$key] = $depthOfNode;
            }
            foreach ($tempArray as $key => $value) {
                $tree[$value][] = $this->nodes[$key]->values;
            }
            ksort($tree);
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
        $tempNode = $this->getRoot()['node'];
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
        $this->nodes[] = new Node ( $values , $parent , $children );
        return [
            "key" => (count( $this->nodes ) -1),
            "node" => $this->nodes[(count( $this->nodes ) -1)]
        ];
    }
}


?>

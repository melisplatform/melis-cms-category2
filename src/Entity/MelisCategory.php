<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCmsCategory2\Entity;

class MelisCategory
{
	protected $id;
	protected $category;
	protected $translations;
	protected $media;
	protected $sites;
	protected $children;

	public function getId()
	{
	    return $this->id;
	}
	
	public function setId($id)
	{
	    $this->id = $id;
	}
	
	public function getCategory()
	{
	    return $this->category;
	}
	
	public function setCategory($category)
	{
	    $this->category = $category;
	}
	
	public function getTranslations()
	{
	    return $this->translations;
	}
	
	public function setTranslations($translations)
	{
	    $this->translations = $translations;
	}
	
	public function getMedia()
	{
	    return $this->media;
	}
	
	public function setMedia($media)
	{
	    $this->media = $media;
	}
	
	public function getSites()
	{
	    return $this->sites;
	}
	
	public function setSites($sites)
	{
	    $this->sites = $sites;
	}
	
	public function getChildren()
	{
	    return $this->children;
	}
	
	public function setChildren($children)
	{
	    $this->children = $children;
	}
	
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
<?php  
namespace Concrete\Package\OpenGraphTagsLite;

use SinglePage;
use Page;
use CollectionAttributeKey;
use \Concrete\Core\Attribute\Type as AttributeType;
use Events;
use \Concrete\Package\OpenGraphTagsLite\Src\Html\OpenGraphTags;

class Controller extends \Concrete\Core\Package\Package {

    protected $pkgHandle = 'open_graph_tags_lite';
    protected $appVersionRequired = '5.7.2';
    protected $pkgVersion = '2.0.2';
    
    public function getPackageDescription()
    {
        return t("Auto insert Open Graph Tags (OGP) into HEAD tag");
    }
    
    public function getPackageName()
    {
        return t("Open Graph Tags Lite");
    }
    
    public function install()
    {
        $pkg = parent::install();
        
        //Add dashboard page
        $sp = SinglePage::add('/dashboard/open_graph_tags_lite', $pkg);
        if (is_object($sp)) {
            $sp->update(array('cName'=>t('Open Graph Tags Lite'), 'cDescription'=>t('Auto insert Open Graph Tags (OGP) into HEAD tag')));
        }
        $sp = SinglePage::add('/dashboard/open_graph_tags_lite/settings', $pkg);
        if (is_object($sp)) {
            $sp->update(array('cName'=>t('Open Graph Tags Settings'), 'cDescription'=>''));
        }
        
        //Add og:image attribute
        $cak = CollectionAttributeKey::getByHandle('og_image');
        if (!is_object($cak)) {
            $at = AttributeType::getByHandle('image_file');
            CollectionAttributeKey::add($at, array('akHandle' => 'og_image', 'akName' => t('og:image')));
        }
    }
    
    public function on_start()
    {
        $ogp = new OpenGraphTags();
        Events::addListener('on_start', array($ogp,'insertTags'));
    }

}
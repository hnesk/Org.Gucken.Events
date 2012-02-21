<?php

namespace Type;
use Type\Url;


/**
 * An Url Class
 *
 * @author jk
 */
class Directory extends Url {
    /**
     *
     * @param string $pattern
     * @return Url\Collection
     */
    public function glob($pattern = '*.*') {
        $urlList = new Url\Collection();
        $it = new \GlobIterator($this->path.'/'.$pattern, \FilesystemIterator::CURRENT_AS_PATHNAME);
        foreach ($it as $file) {
            $urlList->add(new Url('file://'.$file));
        }
        return $urlList;
    }
}
?>
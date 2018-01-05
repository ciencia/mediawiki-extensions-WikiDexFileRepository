<?php
/**
 * Local file in the wiki's own database.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup FileAbstraction
 */

class LocalWikiDexFile extends LocalFile {
	const VERSION = 101; // cache version

	// Copied from LocalFile to avoid self create a LocalFile class instead of
	// a LocalWikiDexFile class
	/**
	 * Create a LocalFile from a title
	 * Do not call this except from inside a repo class.
	 *
	 * Note: $unused param is only here to avoid an E_STRICT
	 *
	 * @param Title $title
	 * @param FileRepo $repo
	 * @param null $unused
	 *
	 * @return LocalFile
	 */
	static function newFromTitle( $title, $repo, $unused = null ) {
		return new self( $title, $repo );
	}

	// Copied from LocalFile to avoid self create a LocalFile class instead of
	// a LocalWikiDexFile class
	/**
	 * Create a LocalFile from a title
	 * Do not call this except from inside a repo class.
	 *
	 * @param stdClass $row
	 * @param FileRepo $repo
	 *
	 * @return LocalFile
	 */
	static function newFromRow( $row, $repo ) {
		$title = Title::makeTitle( NS_FILE, $row->img_name );
		$file = new self( $title, $repo );
		$file->loadFromRow( $row );

		return $file;
	}

	// Copied from LocalFile to avoid self create a LocalFile class instead of
	// a LocalWikiDexFile class
	/**
	 * Create a LocalFile from a SHA-1 key
	 * Do not call this except from inside a repo class.
	 *
	 * @param string $sha1 Base-36 SHA-1
	 * @param LocalRepo $repo
	 * @param string|bool $timestamp MW_timestamp (optional)
	 * @return bool|LocalFile
	 */
	static function newFromKey( $sha1, $repo, $timestamp = false ) {
		$dbr = $repo->getReplicaDB();

		$conds = [ 'img_sha1' => $sha1 ];
		if ( $timestamp ) {
			$conds['img_timestamp'] = $dbr->timestamp( $timestamp );
		}

		$row = $dbr->selectRow( 'image', self::selectFields(), $conds, __METHOD__ );
		if ( $row ) {
			return self::newFromRow( $row, $repo );
		} else {
			return false;
		}
	}

	function __construct( $title, $repo ) {
		$this->repoClass = 'LocalWikiDexRepo';
		parent::__construct( $title, $repo );
	}
	
	/**
	 * Get urlencoded path of the file relative to the public zone root.
	 * This function is overridden in OldLocalFile to be like getArchiveUrl().
	 *
	 * @return string
	 */
	function getUrlRelWithTimestamp() {
		return $this->getHashPath() . 'latest/' . $this->getTimestamp() . '/' . rawurlencode( $this->getName() );
	}

	/**
	 * Copied from File::getURL but changed getUrlRel() by getUrlRelWithTimestamp()
	 *
	 * @return string
	 */
	public function getUrl() {
		if ( !isset( $this->url ) ) {
			$this->assertRepoDefined();
			$ext = $this->getExtension();
			$this->url = $this->repo->getZoneUrl( 'public', $ext ) . '/' . $this->getUrlRelWithTimestamp();
		}

		return $this->url;
	}

	/**
	 * Copied from getZoneUrl, wrap inside if, changed getUrlRel() by getUrlRelWithTimestamp()
	 *
	 * @param string $zone Name of requested zone
	 * @param bool|string $suffix If not false, the name of a file in zone
	 * @return string Path
	 */
	function getZoneUrl( $zone, $suffix = false ) {
		if ( $zone == 'thumb' ) {
			$this->assertRepoDefined();
			$ext = $this->getExtension();
			$path = $this->repo->getZoneUrl( $zone, $ext ) . '/' . $this->getUrlRelWithTimestamp();
			if ( $suffix !== false ) {
				$path .= '/' . rawurlencode( $suffix );
			}
		} else {
			return parent::getZoneUrl( $zone, $suffix );
		}
		return $path;
	}
}

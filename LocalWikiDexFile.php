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

	function __construct( $title, $repo ) {
		$this->repoClass = LocalWikiDexRepo::class;
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
	 * Get the URL of the thumbnail directory, or a particular file if $suffix is specified
	 *
	 * @param bool|string $suffix If not false, the name of a thumbnail file
	 * @return string Path
	 */
	function getThumbUrl( $suffix = false ) {
		return $this->getZoneUrl( 'thumb', $suffix );
	}

	/**
	 * Copied from getZoneUrl, SHOULD BE CALLED ONLY FOR zone = 'thumb',
	 * changed getUrlRel() by getUrlRelWithTimestamp()
	 *
	 * @param string $zone Name of requested zone
	 * @param bool|string $suffix If not false, the name of a file in zone
	 * @return string Path
	 */
	private function getZoneUrl( $zone, $suffix = false ) {
		$this->assertRepoDefined();
		$ext = $this->getExtension();
		$path = $this->repo->getZoneUrl( $zone, $ext ) . '/' . $this->getUrlRelWithTimestamp();
		if ( $suffix !== false ) {
			$path .= '/' . rawurlencode( $suffix );
		}
		return $path;
	}
}

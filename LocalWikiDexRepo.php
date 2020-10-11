<?php
/**
 * Custom file repository for WikiDex
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
 * @ingroup FileRepo
 */

/**
 * A repository that stores files in the local filesystem and registers them
 * in the wiki's own database. This is the most commonly used repository class.
 *
 * @ingroup FileRepo
 */
class LocalWikiDexRepo extends LocalRepo {
	function __construct( array $info = null ) {
		$this->fileFactory = [ LocalWikiDexFile::class, 'newFromTitle' ];
		$this->fileFactoryKey = [ LocalWikiDexFile::class, 'newFromKey' ];
		$this->fileFromRowFactory = [ LocalWikiDexFile::class, 'newFromRow' ];
		parent::__construct( $info );
	}
}

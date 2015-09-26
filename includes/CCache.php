<?php
//=========================================================================================
//	CCache by Chippalrus
//=========================================================================================
/*
	Handler for Caching files
*/
class CCache
{
//=========================================================================================
//	Members
//=========================================================================================
	private		$m_CacheDir;		// Directory for Caching
//=========================================================================================
//	Constructor / Destructor
//=========================================================================================
	protected	function	__construct		( $sDir )
	{
		if( !is_dir( __DIR__ . DIRECTORY_SEPARATOR . $sDir ) )
		{
			mkdir( __DIR__ . DIRECTORY_SEPARATOR . $sDir, 0755 );
		}
		$this->m_CacheDir = __DIR__ . '/' . $sDir . '/';
	}
	
	public	function	__destruct()
	{
		
	}
//=========================================================================================
//	Get
//=========================================================================================
	public	function	GetDirectory	()	{	return $this->m_CacheDir;	}
//=========================================================================================
//	Set
//=========================================================================================
//=========================================================================================
//	Methods
//=========================================================================================
	protected	function	WriteCache		( $sFileName, $sContent, $eURI )
	{
		if( !is_dir( $this->m_CacheDir . $eURI ) )
		{
			mkdir( $this->m_CacheDir . $eURI, 0755 );
		}
		
		if( isset( $sFileName ) )
		{
			$writeJson = fopen( $this->m_CacheDir . $eURI . $sFileName, 'w' );
			fwrite( $writeJson, $sContent );
			fclose( $writeJson );
		}
		else
		{
			echo '0: Cache could not be written. <br/>';
			echo 'PARAM_1 : $sFileName: ' . isset( $sFileName ) . '<br/>';
		}
	}
	
	protected	function	GetCache( $sFileName, $eURI )
	{
		$aTemp = 0;
		if( file_exists( $this->m_CacheDir . $eURI . $sFileName ) )
		{
			$aFileSize = filesize( $this->m_CacheDir . $eURI . $sFileName );
			$aFile = fopen( $this->m_CacheDir . $eURI . $sFileName, 'r' );
			$aTemp = fgets( $aFile );
			fclose( $aFile );
		}
		else	{	$aTemp = 0;		}
		
		return $aTemp;
	}

	protected	function	IsExpired( $sFileName, $eURI )
	{
		$bExpired = true;
		$sFile = $this->m_CacheDir . $eURI . $sFileName;
		if( file_exists( $sFile ) )
		{
			if( filemtime( $sFile ) > strtotime( '-7 hours' ) )
			{
				$bExpired = false;
			} else { $bExpired = true; }
		}
		
		return $bExpired;
	}
}
?>

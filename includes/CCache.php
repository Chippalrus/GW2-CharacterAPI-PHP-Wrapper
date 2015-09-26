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
	private		$m_CacheTime;		// 
//=========================================================================================
//	Constructor / Destructor
//=========================================================================================
	public	function	__construct		( $sDir )
	{
		if( !is_dir( __DIR__ . DIRECTORY_SEPARATOR . $sDir ) )
		{
			mkdir( __DIR__ . DIRECTORY_SEPARATOR . $sDir, 0755 );
		}
		$this->m_CacheDir = __DIR__ . '/' . $sDir . '/';
		$this->m_CacheTime = false;
	}
	
	public	function	__destruct()
	{
		
	}
//=========================================================================================
//	Get
//=========================================================================================
	public	function	GetDirectory	()	{	return $this->m_CacheDir;	}
	public	function	GetCacheTimeOut	()	{	return $this->m_CacheTime;	}
//=========================================================================================
//	Set
//=========================================================================================
	public	function	SetDirectory	(	$sDir	)	{	$this->m_CacheDir	=	$sDir;		}
//=========================================================================================
//	Methods
//=========================================================================================
	public	function	WriteCache		( $sFileName, $sContent, $eURI )
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
	
	public	function	GetCache( $sFileName, $eURI )
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

	public	function	IsExpired( $sFileName, $eURI )
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

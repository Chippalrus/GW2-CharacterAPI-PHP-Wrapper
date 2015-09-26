<?php
require_once( __DIR__ . '/parallelcurl.php' );
require_once( __DIR__ . '/CCache.php' );
require_once( __DIR__ . '/Enum.php' );
//=========================================================================================
//	CGW2API by Chippalrus
//=========================================================================================
/*
	Handler for Items
*/
class CGW2API extends CCache
{
//=========================================================================================
//	Members
//=========================================================================================
	private		$m_ParallelCurl		=	null;
	private		$m_Content			=	null;
//=========================================================================================
//	Constructor / Destructor / Clean Up
//=========================================================================================
	protected	function	__construct		( $iParallelProcesses )
	{
		$this->m_ParallelCurl		=	new ParallelCurl( $iParallelProcesses );
		parent::__construct( 'api_cache' );
		$this->m_Content			=	Array();
	}
	
	public	function	__destruct()
	{
		unset( $this->m_Content );
	}
	
	protected	function	CleaUp()
	{
		unset( $this->m_Content );
		$this->m_Content = Array();
	}
//=========================================================================================
//	Get
//=========================================================================================
	public	function	GetContent( $iID, $eURI ) // json
	{
		$this->CleaUp();
		if( isset( $iID ) )
		{
			if( $this->IsExpired( $iID, $eURI ) )
			{
				$this->SendRequest( $iID, $eURI );
				$this->WriteCache( $iID, $this->m_Content[ 0 ], $eURI );
			}
			else	
			{
				array_push( $this->m_Content, $this->GetCache( $iID, $eURI ) );
			}
		}
		return $this->m_Content[ 0 ];
	}
	
	public	function	GetContentBatch( $aID, $eURI ) //  array json
	{
		$this->CleaUp();
		if( !is_null( $aID) )
		{
			$aList = Array();
			$tempContent = Array();
			for( $i = 0; $i < count( $aID ); $i++ )
			{
				if( $this->IsExpired( $aID[ $i ], $eURI ) )
				{
					array_push( $aList, $aID[ $i ] );
				}
				else
				{
					array_push( $tempContent, $this->GetCache( $aID[ $i ], $eURI ) );
				}
			}
			$iLength = count( $aList );
			if( $iLength > 0 )
			{
				$this->SendRequestList( $aList, $eURI );
				for( $i = 0; $i < $iLength; $i++ )
				{
					$this->WriteCache( $aList[ $i ], $this->m_Content[ $i ], $eURI );
				}
			}
			$this->m_Content = array_merge( $this->m_Content, $tempContent );
		}
		return $this->m_Content;
	}
	
	public	function	GetCharacter( $sName, $sToken )	// json
	{
		$this->CleaUp();
		if( $this->IsExpired( $sName, EURI::CHARACTERS ) )
		{
			if( !is_null( $sName ) && !is_null( $sToken ) )
			{
				$this->SendRequest( $sToken, EURI::CHARACTERS . '/' . $sName . EURI::ACCESS_TOKEN );
				$this->WriteCache( $sName, $this->m_Content[ 0 ], EURI::CHARACTERS );
			}
		}
		else	
		{
			array_push( $this->m_Content, $this->GetCache( $sName, EURI::CHARACTERS ) );
		}
		return $this->m_Content[ 0 ];
	}
//=========================================================================================
//	Functions
//=========================================================================================
	private	function	RequestCompleted( $content, $url, $ch, $search )
	{
		array_push( $this->m_Content, $content );
	}

	private	function	SendRequest( $iID, $eURI )
	{
		$this->m_ParallelCurl->startRequest
		(
			EURI::BASE . $eURI . $iID,
			array( $this, 'RequestCompleted' )
		);
		$this->m_ParallelCurl->finishAllRequests();
	}
	
	private	function	SendRequestList( $iID, $eURI )
	{
		for( $i = 0; $i < count( $iID ); $i++ )
		{
			if( !is_null( $iID[ $i ] ) )
			{
				$this->m_ParallelCurl->startRequest
				(
					EURI::BASE . $eURI . $iID[ $i ],
					array( $this, 'RequestCompleted' )
				);
			}
		}
		$this->m_ParallelCurl->finishAllRequests();
	}
}
?>

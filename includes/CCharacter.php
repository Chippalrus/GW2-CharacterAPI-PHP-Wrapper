<?php
require_once( __DIR__ . '/CGW2API.php' );
//=========================================================================================
//	CCharacter by Chippalrus
//=========================================================================================
class CCharacter extends CGW2API
{
//=========================================================================================
//	Members
//=========================================================================================
	protected	$m_CharacterData		=	null;
	protected	$m_CharacterAttri		=	null;
	protected	$m_Equipments			=	null;
	protected	$m_Specializations		=	null;
//=========================================================================================
//	Constructor / Destructor
//=========================================================================================
	public	function	__construct		()
	{
		parent::__construct( 20 );
		$this->m_CharacterData		=	Array();
		$this->m_CharacterAttri		=	Array();
		$this->m_Equipments			=	Array();
			$this->m_Equipments[ EItemType::EQUIPMENT ]	=	Array();
			$this->m_Equipments[ EItemType::UPGRADES ]	=	Array();
			$this->m_Equipments[ EItemType::INFUSIONS ]	=	Array();

		$this->m_Specializations	=	Array();
			$this->m_Specializations[ ESpecialization::A ]	=	Array();
			$this->m_Specializations[ ESpecialization::B ]	=	Array();
			$this->m_Specializations[ ESpecialization::C ]	=	Array();
	}
	
	public	function	__destruct()
	{
		parent::__destruct();
	}
//=========================================================================================
//	Get
//=========================================================================================
	public	function	GetName		(){	return $this->m_CharacterData->{	'name'		};	}
	public	function	GetRace		(){	return $this->m_CharacterData->{	'race' 		};	}
	public	function	GetGender	(){	return $this->m_CharacterData->{	'gender' 	};	}
	public	function	GetProfession	(){	return $this->m_CharacterData->{	'profession' 	};	}
	public	function	GetLevel	(){	return $this->m_CharacterData->{	'level' 	};	}
	public	function	GetAge		(){	return $this->m_CharacterData->{	'age' 		};	}
	public	function	GetDeaths	(){	return $this->m_CharacterData->{	'deaths' 	};	}
//=========================================================================================
//	Set
//=========================================================================================	
	public	function	SetEquipmentData()
	{
		//	Get Equipment/Upgrades/Infusions Data
		$aEquipmentID = Array();
		$aEquipmentID[ EItemType::EQUIPMENT ] = Array();
		$aEquipmentID[ EItemType::UPGRADES ] = Array();
		$aEquipmentID[ EItemType::INFUSIONS ] = Array();
		// Grab IDs and omit duplicates from request
		$iLength = count( $this->m_CharacterData->{ 'equipment' } );
		for( $i = 0; $i < $iLength; $i++ )
		{
			// Equipment
			$bMatchedID = false;
			// Get the ID of the Item
			$tempID = $this->m_CharacterData->{ 'equipment' }[ $i ]->{ 'id' };
			// Loop through temp array
			for( $j = 0; $j < count( $aEquipmentID ); $j++ )
			{
				// Check for duplicate ID
				if( !is_null( $tempID ) && $tempID != $aEquipmentID[ EItemType::EQUIPMENT ][ $j ] )
				{
					$bMatchedID = false;
				}
				else
				{
					// If there is a duplicate break out of the loop
					$bMatchedID = true;
					break;
				}
			}
			// If no duplicate ID then add the ID into it.
			if( !$bMatchedID )	{	array_push( $aEquipmentID[ EItemType::EQUIPMENT ], $tempID );	}
			
			// Upgrades
			// Get the IDs of the upgrade
			$tempID = $this->m_CharacterData->{ 'equipment' }[ $i ]->{ 'upgrades' };
			// Loop through number of Upgrades
			for( $k = 0; $k < count( $tempID ); $k++ )
			{
				$bMatchedID = false;
				// Loop through temp array
				for( $j = 0; $j < count( $aEquipmentID ); $j++ )
				{
					// Check if there is a duplicate Upgrade ID
					if( !is_null( $tempID[ $k ] ) && $tempID[ $k ] != $aEquipmentID[ EItemType::UPGRADES ][ $j ] )
					{
						$bMatchedID = false;
					}
					else
					{
						// If there is a duplicate break out of the loop
						$bMatchedID = true;
						break;
					}
				}
				// If there is no duplicate Upgrade ID add ID to it.
				if( !$bMatchedID )	{	array_push( $aEquipmentID[ EItemType::UPGRADES ], $tempID[ $k ] );	}
			}
			// Infusions
			// Get the IDs of the Infusions
			$tempID = $this->m_CharacterData->{ 'equipment' }[ $i ]->{ 'infusions' };
			// Loop through number of Infusions
			for( $k = 0; $k < count( $tempID ); $k++ )
			{
				$bMatchedID = false;
				// Loop through temp array
				for( $j = 0; $j < count( $aEquipmentID ); $j++ )
				{
					// Check if there is a duplicate Infusion ID
					if( !is_null( $tempID[ $k ] ) && $tempID[ $k ] != $aEquipmentID[ EItemType::INFUSIONS ][ $j ] )
					{
						$bMatchedID = false;
					}
					else
					{
						// If there is a duplicate break out of the loop
						$bMatchedID = true;
						break;
					}
				}
				// If there is no duplicate Infusion ID add ID to it.
				if( !$bMatchedID )	{	array_push( $aEquipmentID[ EItemType::INFUSIONS ], $tempID[ $k ] );	}
			}
		}

		//	Request from API
		$aEquipmentID[ EItemType::EQUIPMENT ] = $this->GetContentBatch( $aEquipmentID[ EItemType::EQUIPMENT ], EURI::ITEMS );
		$aEquipmentID[ EItemType::UPGRADES ] = $this->GetContentBatch( $aEquipmentID[ EItemType::UPGRADES ], EURI::ITEMS );
		$aEquipmentID[ EItemType::INFUSIONS ] = $this->GetContentBatch( $aEquipmentID[ EItemType::INFUSIONS ], EURI::ITEMS );

		//	Organize into Array 
		$mEquipments = Array();	
		
		// Equipment
		$mEquipments[ EItemType::EQUIPMENT ]	= &$this->m_Equipments[ EItemType::EQUIPMENT ];	
		$iLength = count( $this->m_CharacterData->{ 'equipment' } );
		for( $i = 0; $i < count( $aEquipmentID[ EItemType::EQUIPMENT ] ); $i++ )
		{
			// Decode the json files
			$aEquipmentID[ EItemType::EQUIPMENT ][ $i ] = json_decode( $aEquipmentID[ EItemType::EQUIPMENT ][ $i ] );
			
			// Loop through Character data "Equipments"
			for( $j = 0; $j < $iLength; $j++ )
			{
				// Check for idendical item ID from decoded files
				$tempEquipment = $this->m_CharacterData->{ 'equipment' }[ $j ];
				if( $aEquipmentID[ EItemType::EQUIPMENT ][ $i ]->{ 'id' } == $tempEquipment->{ 'id' } )
				{
					// Store into its slot
					$mEquipments[ EItemType::EQUIPMENT ][ $tempEquipment->{ 'slot' } ] = $aEquipmentID[ EItemType::EQUIPMENT ][ $i ];
				}
			}
		}
		// Upgrades
		$mEquipments[ EItemType::UPGRADES ]		= &$this->m_Equipments[ EItemType::UPGRADES ];
		for( $i = 0; $i < count( $aEquipmentID[ EItemType::UPGRADES ] ); $i++ )
		{
			// Decode the json files
			$aEquipmentID[ EItemType::UPGRADES ][ $i ] = json_decode( $aEquipmentID[ EItemType::UPGRADES ][ $i ] );
			
			// Loop through Character data "Equipments"
			for( $j = 0; $j < $iLength; $j++ )
			{
				// Check for idendical item ID from decoded files
				$tempEquipment = $this->m_CharacterData->{ 'equipment' }[ $j ];
				for( $k = 0; $k < count( $tempEquipment->{ 'upgrades' } ); $k++ )
				{
					if( $aEquipmentID[ EItemType::UPGRADES ][ $i ]->{ 'id' } == $tempEquipment->{ 'upgrades' }[ $k ] )
					{
						// Store into its slot
						if( is_null( $mEquipments[ EItemType::UPGRADES ][ $tempEquipment->{ 'slot' } ] ) )
						{
							$mEquipments[ EItemType::UPGRADES ][ $tempEquipment->{ 'slot' } ] = $aEquipmentID[ EItemType::UPGRADES ][ $i ];
						}
						// If the slot is already filled and of the same slot then it is likely a Two-handed weapon
						else if( $tempEquipment->{ 'slot' } == 'WeaponA1' )
						{
							// Store it into the weapon 2 slot instead
							$mEquipments[ EItemType::UPGRADES ][ 'WeaponA2' ] = $aEquipmentID[ EItemType::UPGRADES ][ $i ];
						}
						else if( $tempEquipment->{ 'slot' } == 'WeaponB1' )
						{
							$mEquipments[ EItemType::UPGRADES ][ 'WeaponB2' ] = $aEquipmentID[ EItemType::UPGRADES ][ $i ];	
						}
					}
				}
			}
		}
		// Infusions
		$mEquipments[ EItemType::INFUSIONS ]	= &$this->m_Equipments[ EItemType::INFUSIONS ];
		for( $i = 0; $i < count( $aEquipmentID[ EItemType::INFUSIONS ] ); $i++ )
		{
			// Decode the json files
			$aEquipmentID[ EItemType::INFUSIONS ][ $i ] = json_decode( $aEquipmentID[ EItemType::INFUSIONS ][ $i ] );
			
			// Loop through Character data "Equipments"
			for( $j = 0; $j < $iLength; $j++ )
			{
				// Check for idendical item ID from decoded files
				$tempEquipment = $this->m_CharacterData->{ 'equipment' }[ $j ];
				for( $k = 0; $k < count( $tempEquipment->{ 'infusions' } ); $k++ )
				{
					if( $aEquipmentID[ EItemType::INFUSIONS ][ $i ]->{ 'id' } == $tempEquipment->{ 'infusions' }[ $k ] )
					{
						// Store into its slot
						if( is_null( $mEquipments[ EItemType::INFUSIONS ][ $tempEquipment->{ 'slot' } ] ) )
						{
							$mEquipments[ EItemType::INFUSIONS ][ $tempEquipment->{ 'slot' } ] = $aEquipmentID[ EItemType::INFUSIONS ][ $i ];
						}
						// If the slot is already filled and of the same slot then it is likely a Two-handed weapon
						else if( $tempEquipment->{ 'slot' } == 'WeaponA1' )
						{
							// Store it into the weapon 2 slot instead
							$mEquipments[ EItemType::INFUSIONS ][ 'WeaponA2' ] = $aEquipmentID[ EItemType::INFUSIONS ][ $i ];
						}
						else if( $tempEquipment->{ 'slot' } == 'WeaponB1' )
						{
							$mEquipments[ EItemType::INFUSIONS ][ 'WeaponB2' ] = $aEquipmentID[ EItemType::INFUSIONS ][ $i ];	
						}
					}
				}
			}
		}
	}
	
	public	function	SetBuildData( $SpecMode )
	{
		// Get Specialization IDs of specific mode
		$aSpecIDs = Array();
		for( $i = 0; $i < count( $this->m_CharacterData->{ 'specializations' }->{ $SpecMode } ); $i++ )
		{
			array_push( $aSpecIDs, $this->m_CharacterData->{ 'specializations' }->{ $SpecMode }[ $i ]->{ 'id' } );
		}
		// Request for specialization data
		$aSpecIDs = $this->GetContentBatch( $aSpecIDs, EURI::SPECIALIZATIONS );
		
		// Loop through each specialization
		for( $i = ESpecialization::A; $i < ESpecialization::MAX; $i++ )
		{
			// decode the content and store it
			$this->m_Specializations[ $i ][ ETrait::_S ] = json_decode( $aSpecIDs[ $i ] );
			
			$aTraitIDs = Array();
			// Loop through max amount of traits
			for( $j = ETrait::_0; $j < ETrait::MAX; $j++ )
			{
				if( $j < ETrait::_3 )
				{
					// Get the minor trait IDs
					array_push( $aTraitIDs, $this->m_Specializations[ $i ][ ETrait::_S ]->{ 'minor_traits' }[ $j ] );
				}
				else
				{
					// Get the major trait IDs
					array_push( $aTraitIDs, $this->m_Specializations[ $i ][ ETrait::_S ]->{ 'major_traits' }[ $j - 3 ] );
				}
			}

			// Request for trait data
			$aTraitIDs = $this->GetContentBatch( $aTraitIDs, EURI::TRAITS );
			// Loop through max amount of traits ... again
			for( $j = ETrait::_0; $j < ETrait::MAX; $j++ )
			{
				// Decode the content and store it
				$this->m_Specializations[ $i ][ $j ] = json_decode( $aTraitIDs[ $j ] );
			}
		}
	}
//=========================================================================================
//	Request functions
//=========================================================================================
	public	function	SetCharacterData( $sCharacterName, $sMode, $sAPIKey )
	{
		// Get Character Data
		$CharData = $this->GetCharacter( $sCharacterName, $sAPIKey );	
		$this->m_CharacterData	= json_decode( $CharData );

		// Character Equipment and its Data
		$this->SetEquipmentData();
		
		// Specialization and Trait Data based on wvw/pve/pvp
		$this->SetBuildData( $sMode );
	}
}

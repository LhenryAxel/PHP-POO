<?php
namespace App\Models\Objects;

use App\Models\Model;
use App\Models\Objects\DatabaseObject;
use App\Models\UserModel;
use DateTime;
use Exception;

class Page extends DatabaseObject {
	private int|null $id;
	private string|null $title;
	private string|null $slug;
	private string|null $content;
	private int|null $createdBy;
	private User|null $CreatedByUser;
	private DateTime|null $CreatedAt;


	#region Getters 
	public function GetId(): int|null {
		return $this->id;
	}

	public function GetTitle(): string|null {
		return $this->title;
	}

	public function GetSlug(): string|null {
		return $this->slug;
	}

	public function GetContent(): string|null {
		return $this->content;
	}

	public function GetCreatedBy(): int|null {
		return $this->createdBy;
	}

	public function GetCreatedByUser(): User|null {
		return $this->CreatedByUser;
	}

	public function GetCreatedAt(): DateTime|null {
		return $this->CreatedAt;
	}

	/**
	 * Return the date in an "day month year" format
	 * @return string|null
	 */
	public function GetCreatedAtAsString(): string|null {
		if ($this->CreatedAt === null) {
			return null;
		}

		return $this->CreatedAt->format("d M Y");
	}
	#endregion


	#region Setters 
	public function SetId (int|null $id): void {
		$this->id = $id;
	}

	public function SetTitle(string|null $title): void {
		$this->title = $title;
	}

	public function SetSlug(string|null $slug): void {
		$this->slug = $slug;
	}

	public function SetContent(string|null $content): void {
		$this->content = $content;
	}

	/**
	 * Summary of SetCreatedBy
	 * @param int|null $createdBy
	 * @param bool $associateReference if true a User::getById request will be sent and the {propertie CreatedByUser} will be set to its result
	 * @return void
	 */
	public function SetCreatedBy(int|null $createdBy, bool $associateReference = false): void {
		if ($createdBy === null) {
			$this->createdBy = null;
			$this->CreatedByUser = null;
		}
		
		if ($associateReference) {
			try {
				$this->SetCreatedByUser(
					CreatedByUser: UserModel::GetInstance()->GetById($createdBy)
				);
				return;
			} catch (Exception $e) {
				
			}
		}

		$this->createdBy = $createdBy;
		$this->CreatedByUser = null;
	}

	public function SetCreatedByUser(User|null $CreatedByUser): void {
		$this->CreatedByUser = $CreatedByUser;
		$this->createdBy = $this->CreatedByUser->GetId();
	}

	public function SetCreatedAt(DateTime|null $created_at): void {
		$this->CreatedAt = $created_at;
	}

	public function SetCreatedAtFromString(string|null $created_at): void {
		if ($created_at === null) {
			$this->CreatedAt = null;
			return;
		}

		$this->CreatedAt = new DateTime($created_at);
	}
	#endregion


	public function __construct(
		int|null $id = null, 
		string|null $title = null, 
		string|null $slug = null, 
		string|null $content = null, 
		int|null $created_by = null, 
		DateTime|null $created_at = null
	) {
		$this->id = $id;
		$this->title = $title;
		$this->slug = $slug;
		$this->content = $content;
		$this->createdBy = $created_by;
		$this->CreatedAt = $created_at;
	}


	public static function NewObject(array $data, bool $associateReference = false): self {
		$instance = new self;
		
		$instance->SetId($data["id"]);
		$instance->SetTitle($data["title"]);
		$instance->SetSlug($data["slug"]);
		$instance->SetContent($data["content"]);
		$instance->SetCreatedBy($data["created_by"], $associateReference);
		$instance->SetCreatedAtFromString($data["created_at"] ?? null);
		
		return $instance;
	}
}
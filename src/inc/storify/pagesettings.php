<?php
namespace storify;

class pagesettings{
	private $pages;

	function __construct($userID){

		$user = $userID ? "user@".$userID : "user";

		$this->pages = array();
		$this->pages["home"] = array(
			"meta"=>array(
				"name"=>"Beautiful Stories By Everyday People - Storify",
				"description"=>"Storify.me is a self-serve marketplace connecting brands with everyday micro-influencers who create beautiful stories with the products they love.",
				"canonical"=>"https://storify.me"
			),
			"og"=>array(
				"og:type"=>"website",
				"og:title"=>"Beautiful Stories By Everyday People - Storify",
				"og:description"=>"Storify.me is a self-serve marketplace connecting brands with everyday micro-influencers who create beautiful stories with the products they love.",
				"og:url"=>"https://storify.me",
				"og:site_name"=>"Storify.Me",
				"og:image"=>"http://cdn.storify.me/data/uploads/2018/12/storify_homepage.jpg",
				"og:image:secure_url"=>"https://cdn.storify.me/data/uploads/2018/12/storify_homepage.jpg",
				"og:image:type"=>"image/jpeg",
				"og:image:width"=>1200,
				"og:image:height"=>630
			),
			"breadcrumb"=>array(),
			"header"=>"",
			"subheader"=>"",
			"pageindex"=>"home"
		);

		$this->pages["listing"] = array(
			"meta"=>array(
				"name"=>"<#> <passion> creators from <country> - Storify",
				"description"=>"Meet the everyday creators who produce beautiful stories of the brands they love.",
				"canonical"=>"https://storify.me"
			),
			"og"=>array(
				"og:type"=>"website",
				"og:title"=>"<#> <passion> creators from <country> - Storify ",
				"og:description"=>"Meet the everyday creators who produce beautiful stories of the brands they love.",
				"og:url"=>"https://storify.me",
				"og:site_name"=>"Storify.Me"
			),
			"breadcrumb"=>array(
				array(
					"label"=>"Home",
					"href"=>"/"
				),
				array(
					"label"=>"Search",
					"href"=>""
				)
			),
			"header"=>"Header",
			"subheader"=>"Subheader",
			"pageindex"=>"listing"
		);

		$this->pages["submitcreator"] = array(
			"meta"=>array(
				"name"=>"Submit a content creator for Storify",
				"description"=>"Storify.me is a self-serve marketplace connecting brands with everyday micro-influencers who create beautiful stories with the products they love.",
				"canonical"=>"https://storify.me/submitcreator"
			),
			"og"=>array(
				"og:type"=>"website",
				"og:title"=>"Submit a content creator for Storify",
				"og:description"=>"Storify.me is a self-serve marketplace connecting brands with everyday micro-influencers who create beautiful stories with the products they love.",
				"og:url"=>"https://storify.me/submitcreator",
				"og:site_name"=>"Storify.Me"
			),
			"breadcrumb"=>array(
				array(
					"label"=>"Home",
					"href"=>"/"
				),
				array(
					"label"=>"Search",
					"href"=>""
				)
			),
			"header"=>"Header",
			"subheader"=>"Subheader",
			"pageindex"=>"listing"
		);

		$this->pages["iger"] = array(
			"meta"=>array(
				"name"=>"Storify.me",
				"description"=>get_bloginfo("description"),
				"canonical"=>"https://storify.me"
			),
			"og"=>array(
				"og:type"=>"profile",
			),
			"breadcrumb"=>array(
				array(
					"label"=>"Home",
					"href"=>"/"
				),
				array(
					"label"=>"Iger",
					"href"=>""
				)
			),
			"header"=>"Header",
			"subheader"=>"Subheader",
			"pageindex"=>"iger"
		);

		$this->pages["signin"] = array(
			"meta"=>array(
				"name"=>"Sign In - Storify",
				"description"=>'Sign in to Storify - the marketplace for everyday creators and the brands they love.',
				"canonical"=>"https://storify.me/signin"
			),
			"og"=>array(
				"og:type"=>"website",
				"og:title"=>"Sign In - Storify",
				"og:description"=>'Sign in to Storify - the marketplace for everyday creators and the brands they love.',
				"og:url"=>"https://storify.me/signin",
				"og:site_name"=>"Storify.Me",
				"og:image"=>"http://cdn.storify.me/data/uploads/2018/12/storify_homepage.jpg",
				"og:image:secure_url"=>"https://cdn.storify.me/data/uploads/2018/12/storify_homepage.jpg",
				"og:image:type"=>"image/jpeg",
				"og:image:width"=>1200,
				"og:image:height"=>630
			),
			"breadcrumb"=>array(
				array(
					"label"=>"Home",
					"href"=>"/"
				),
				array(
					"label"=>"Sign In",
					"href"=>""
				)
			),
			"header"=>"",
			"subheader"=>"",
			"pageindex"=>"signin"
		);

		$this->pages["signup"] = array(
			"meta"=>array(
				"name"=>"Sign up - Storify",
				"description"=>'Create an account for Storify - the marketplace for everyday creators and the brands they love.',
				"canonical"=>"https://storify.me/signup"
			),
			"og"=>array(
				"og:type"=>"website",
				"og:title"=>"Sign up - Storify",
				"og:description"=>'Create an account for Storify - the marketplace for everyday creators and the brands they love.',
				"og:url"=>"https://storify.me/signup",
				"og:site_name"=>"Storify.Me",
				"og:image"=>"http://cdn.storify.me/data/uploads/2018/12/storify_homepage.jpg",
				"og:image:secure_url"=>"https://cdn.storify.me/data/uploads/2018/12/storify_homepage.jpg",
				"og:image:type"=>"image/jpeg",
				"og:image:width"=>1200,
				"og:image:height"=>630
			),
			"breadcrumb"=>array(
				array(
					"label"=>"Home",
					"href"=>"/"
				),
				array(
					"label"=>"Sign Up",
					"href"=>""
				)
			),
			"header"=>"",
			"subheader"=>"",
			"pageindex"=>"signup"
		);

		$this->pages["showcase"] = array(
			"meta"=>array(
				"name"=>"Social Showcase - Storify",
				"description"=>get_bloginfo("description"),
				"canonical"=>"https://storify.me"
			),
			"og"=>NULL,
			"breadcrumb"=>array(
				array(
					"label"=>"Home",
					"href"=>"/"
				),
				array(
					"label"=>"Social Showcase",
					"href"=>""
				)
			),
			"header"=>"",
			"subheader"=>"",
			"pageindex"=>$user."/showcase"
		);

		$this->pages["performance"] = array(
			"meta"=>array(
				"name"=>"Performance - Storify",
				"description"=>get_bloginfo("description"),
				"canonical"=>"https://storify.me"
			),
			"og"=>NULL,
			"breadcrumb"=>array(
				array(
					"label"=>"Home",
					"href"=>"/"
				),
				array(
					"label"=>"Performance",
					"href"=>""
				)
			),
			"header"=>"",
			"subheader"=>"",
			"pageindex"=>$user."/performance"
		);

		$this->pages["projects"] = array(
			"meta"=>array(
				"name"=>"Projects - Storify",
				"description"=>get_bloginfo("description"),
				"canonical"=>"https://storify.me"
			),
			"og"=>NULL,
			"breadcrumb"=>array(
				array(
					"label"=>"Home",
					"href"=>"/"
				),
				array(
					"label"=>"Projects",
					"href"=>""
				)
			),
			"header"=>"",
			"subheader"=>"",
			"pageindex"=>$user."/projects"
		);

		$this->pages["projects_invited"] = array(
			"meta"=>array(
				"name"=>"Projects - Invited - Storify",
				"description"=>get_bloginfo("description"),
				"canonical"=>"https://storify.me"
			),
			"og"=>NULL,
			"breadcrumb"=>array(
				array(
					"label"=>"Home",
					"href"=>"/"
				),
				array(
					"label"=>"Projects",
					"href"=>""
				),
				array(
					"label"=>"Invited",
					"href"=>""
				)
			),
			"header"=>"",
			"subheader"=>"",
			"pageindex"=>$user."/projects/invited"
		);

		$this->pages["projects_ongoing"] = array(
			"meta"=>array(
				"name"=>"Projects - Ongoing - Storify",
				"description"=>get_bloginfo("description"),
				"canonical"=>"https://storify.me"
			),
			"og"=>NULL,
			"breadcrumb"=>array(
				array(
					"label"=>"Home",
					"href"=>"/"
				),
				array(
					"label"=>"Projects",
					"href"=>""
				),
				array(
					"label"=>"Ongoing",
					"href"=>""
				)
			),
			"header"=>"",
			"subheader"=>"",
			"pageindex"=>$user."/projects/ongoing"
		);

		$this->pages["projects_closed"] = array(
			"meta"=>array(
				"name"=>"Projects - Closed - Storify",
				"description"=>get_bloginfo("description"),
				"canonical"=>"https://storify.me"
			),
			"og"=>NULL,
			"breadcrumb"=>array(
				array(
					"label"=>"Home",
					"href"=>"/"
				),
				array(
					"label"=>"Projects",
					"href"=>""
				),
				array(
					"label"=>"Closed",
					"href"=>""
				)
			),
			"header"=>"",
			"subheader"=>"",
			"pageindex"=>$user."/projects/closed"
		);

		$this->pages["profile"] = array(
			"meta"=>array(
				"name"=>"Profile - Storify",
				"description"=>get_bloginfo("description"),
				"canonical"=>"https://storify.me"
			),
			"og"=>NULL,
			"breadcrumb"=>array(
				array(
					"label"=>"Home",
					"href"=>"/"
				),
				array(
					"label"=>"Profile",
					"href"=>""
				)
			),
			"header"=>"",
			"subheader"=>"",
			"pageindex"=>$user."/profile"
		);

		$this->pages["updatepassword"] = array(
			"meta"=>array(
				"name"=>"Update Password - Storify",
				"description"=>get_bloginfo("description"),
				"canonical"=>"https://storify.me"
			),
			"og"=>NULL,
			"breadcrumb"=>array(
				array(
					"label"=>"Home",
					"href"=>"/"
				),
				array(
					"label"=>"Update Password",
					"href"=>""
				)
			),
			"header"=>"",
			"subheader"=>"",
			"pageindex"=>$user."/password"
		);

		$this->pages["collections"] = array(
			"meta"=>array(
				"name"=>"Collections - Storify",
				"description"=>get_bloginfo("description"),
				"canonical"=>"https://storify.me"
			),
			"og"=>NULL,
			"breadcrumb"=>array(
				array(
					"label"=>"Home",
					"href"=>"/"
				),
				array(
					"label"=>"Collections",
					"href"=>""
				)
			),
			"header"=>"",
			"subheader"=>"",
			"pageindex"=>$user."/collections"
		);

		$this->pages["people_bookmark"] = array(
			"meta"=>array(
				"name"=>"Creator Bookmark - Storify",
				"description"=>get_bloginfo("description"),
				"canonical"=>"https://storify.me"
			),
			"og"=>NULL,
			"breadcrumb"=>array(
				array(
					"label"=>"Home",
					"href"=>"/"
				),
				array(
					"label"=>"Collections",
					"href"=>"/".$user."/collections"
				),
				array(
					"label"=>"Creators",
					"href"=>""
				)
			),
			"header"=>"",
			"subheader"=>"",
			"pageindex"=>$user."/people"
		);

		$this->pages["story_bookmark"] = array(
			"meta"=>array(
				"name"=>"Story Bookmark - Storify",
				"description"=>get_bloginfo("description"),
				"canonical"=>"https://storify.me"
			),
			"og"=>NULL,
			"breadcrumb"=>array(
				array(
					"label"=>"Home",
					"href"=>"/"
				),
				array(
					"label"=>"Collections",
					"href"=>"/".$user."/collections"
				),
				array(
					"label"=>"Stories",
					"href"=>""
				)
			),
			"header"=>"",
			"subheader"=>"",
			"pageindex"=>$user."/stories"
		);

		$this->pages["people_folder_listing"] = array(
			"meta"=>array(
				"name"=>"Creator Board List - Storify",
				"description"=>get_bloginfo("description"),
				"canonical"=>"https://storify.me"
			),
			"og"=>NULL,
			"breadcrumb"=>array(
				array(
					"label"=>"Home",
					"href"=>"/"
				),
				array(
					"label"=>"Collections",
					"href"=>"/".$user."/collections"
				),
				array(
					"label"=>"Creator Boards",
					"href"=>""
				)
			),
			"header"=>"",
			"subheader"=>"",
			"pageindex"=>$user."/collections/people"
		);

		$this->pages["story_folder_listing"] = array(
			"meta"=>array(
				"name"=>"Story Baord List - Storify",
				"description"=>get_bloginfo("description"),
				"canonical"=>"https://storify.me"
			),
			"og"=>NULL,
			"breadcrumb"=>array(
				array(
					"label"=>"Home",
					"href"=>"/"
				),
				array(
					"label"=>"Collections",
					"href"=>"/".$user."/collections"
				),
				array(
					"label"=>"Story Boards",
					"href"=>""
				)
			),
			"header"=>"",
			"subheader"=>"",
			"pageindex"=>$user."/collections/story"
		);

		$this->pages["people_folder"] = array(
			"meta"=>array(
				"name"=>get_bloginfo("name"),
				"description"=>get_bloginfo("description"),
				"canonical"=>"https://storify.me"
			),
			"og"=>NULL,
			"breadcrumb"=>array(
				array(
					"label"=>"Home",
					"href"=>"/"
				),
				array(
					"label"=>"Collections",
					"href"=>"/".$user."/collections"
				),
				array(
					"label"=>"Creator Boards",
					"href"=>"/".$user."/people"
				)
			),
			"header"=>"",
			"subheader"=>"",
			"pageindex"=>$user."/collections/people/0"
		);

		$this->pages["story_folder"] = array(
			"meta"=>array(
				"name"=>get_bloginfo("name"),
				"description"=>get_bloginfo("description"),
				"canonical"=>"https://storify.me"
			),
			"og"=>NULL,
			"breadcrumb"=>array(
				array(
					"label"=>"Home",
					"href"=>"/"
				),
				array(
					"label"=>"Collections",
					"href"=>"/".$user."/collections"
				),
				array(
					"label"=>"Story Boards",
					"href"=>"/".$user."/stories"
				)
			),
			"header"=>"",
			"subheader"=>"",
			"pageindex"=>$user."/collections/story/0"
		);

		$this->pages["forgot_password"] = array(
			"meta"=>array(
				"name"=>get_bloginfo("name"),
				"description"=>get_bloginfo("description"),
				"canonical"=>"https://storify.me"
			),
			"og"=>NULL,
			"breadcrumb"=>array(
				array(
					"label"=>"Home",
					"href"=>"/"
				),
				array(
					"label"=>"Reset Password",
					"href"=>""
				)
			),
			"header"=>"",
			"subheader"=>"",
			"pageindex"=>$user."/collections/story/0"
		);

		$this->pages["reset_password"] = array(
			"meta"=>array(
				"name"=>get_bloginfo("name"),
				"description"=>get_bloginfo("description"),
				"canonical"=>"https://storify.me"
			),
			"og"=>NULL,
			"breadcrumb"=>array(
				array(
					"label"=>"Home",
					"href"=>"/"
				),
				array(
					"label"=>"New Password",
					"href"=>""
				)
			),
			"header"=>"",
			"subheader"=>"",
			"pageindex"=>$user."/collections/story/0"
		);
	}

	public function getSettings($pageindex){
		if(isset($this->pages[$pageindex])){
			$meta = $this->pages[$pageindex];
			if(!$meta["og"]){
				$home = $this->pages["home"];
				$meta["og"] = $home["og"];
			}
			return $meta;
		}else{
			//return default
			return $this->pages["home"];
		}
	}
}
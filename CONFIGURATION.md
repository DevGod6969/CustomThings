# Basic configuration

```json
[
  {
    "type": "basic",
    "name": "custom:ruby",
    "texture": "ruby",
    "id": 2000,
    "meta": 0
  }
]
```

`type` property is used to specify the type of item you want to create

`name` property is used to identify your item and to rename it with the texture pack

`texture` property is the name of your item in your texture pack

`id` property is to identify the item. They must all be different

`meta` property is to identify the item version. Not required if equal to 0

# Tools configuration

```json
[
  {
    // BASIC CONFIGURATION HERE
    "type": "tool",
    "max_durability": 2000,
    "damage": 350
  }
]
```

`max_durability` property is to set the max_durability of your tool

`damage` property is the damage you will apply with your tool

# Armors configuration

```json
[
  {
    // BASIC CONFIGURATION HERE
    "type": "helmet",
    "max_durability": 2000,
    "defense_point": 200,
    "protection": 5
  }
]
```

`type` property for armor are : helmet, chestplate, leggings and boots

`max_durability` property is to set the max_durability of your tool

`defense_point` property is the defense points of your armor

`protection` property is for the protection bar above your life bar
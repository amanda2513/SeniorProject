def add_category_with_all_details_required(category_name,subcategory_name, subcategory_desc, points)
  fill_in "category_name", :with => category_name
  fill_in "subcategory[0][name]", :with => subcategory_name
  fill_in "subcategory[0][criteria][0][desc]", :with => subcategory_desc
  fill_in "subcategory[0][criteria][0][points]", :with => points
end


def add_category_with_all_details_required_with_additional_criteria(category_name,subcategory_name, subcategory_desc, points, additional_subcategory_desc, additional_subcategory_points )
  fill_in "category_name", :with => category_name
  fill_in "subcategory[0][name]", :with => subcategory_name
  fill_in "subcategory[0][criteria][0][desc]", :with => subcategory_desc
  fill_in "subcategory[0][criteria][0][points]", :with => points
  find(:xpath, ".//*[@id='add_subcat0_criteria']").click
  fill_in "subcategory[0][criteria][2][desc]", :with => additional_subcategory_desc
  fill_in "subcategory[0][criteria][2][points]", :with => additional_subcategory_points
end

def add_subcategory(subcategory_1_name, subcategory_1_desc, points_1)
  fill_in "subcategory[1][name]", :with => subcategory_1_name
  fill_in "subcategory[1][criteria][1][desc]", :with =>  subcategory_1_desc
  fill_in "subcategory[1][criteria][1][points]", :with => points_1

end
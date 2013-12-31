Given(/^I am on the system setting page$/) do
  visit "http://gradproject.dynu.com/settings/general"
end

When(/^I change System settings$/) do
  fill_in 'exhib_location', :with =>  "test location"
  fill_in "judges_per_project", :with => "5"
  fill_in "projects_per_judge", :with => "3"
  select('On', :from => 'restrict_access')

end

When(/^click on save$/) do
  find_button("Save Settings").click
end

Then(/^the systems settings should be saved$/) do
  page.should have_content("Settings Have Been Updated")
end

When(/^I am on the system setting page to verify changed settings$/) do
  visit "http://gradproject.dynu.com/settings/general"
end

Then(/^the systems settings should be the one we changed earlier$/) do
  find(:xpath, "//*[@id='exhib_location']").value == "test location"
  find(:xpath, "//*[@id='judges_per_project']").value == "5"
  find(:xpath, "//*[@id='projects_per_judge']").value == "3"
  find_field('restrict_access').find('option[selected]').text == "On"
end

Given(/^I am on project category page$/) do
  visit "http://gradproject.dynu.com/settings/categories"
end

When(/^I click on Add category button$/) do
  #find(:xpath, "//*[@id='btn_add_category']")['href'] == "http://gradproject.dynu.com/settings/categories/add"
  find('a#btn_add_category')['href'].should include("http://gradproject.dynu.com/settings/categories/add")
  #find(:xpath, "//*[@id='btn_add_category']").click
  find('a#btn_add_category').click
  sleep 3
end

Then(/^I should see "(.*?)", "(.*?)", "(.*?)" buttons$/) do |subcategory_button, submit_button, addcriteria_button|
  first(:button, subcategory_button)
  first(:button, submit_button)
  first(:button, addcriteria_button)
  end

#Given(/^I click on "(.*?)" button to add a category$/) do |button_name|
#  first(:button, button_name).click
#end

When(/^I enter all the fields$/) do
  @user_details = UserDetails.new
  @category_name = @user_details.get_category_name
  @subcategory_name = @user_details.get_subcategory_name
  @subcategory_desc = @user_details.get_subcategory_desc
  @points = "10"
  add_category_with_all_details_required(@category_name,@subcategory_name, @subcategory_desc, @points)
end

When(/^Click on "(.*?)" Button$/) do |button_name|
  #find(:xpath, "//*[@id='submit_category_btn']").click
  first(:button, button_name).click
end

Then(/^I should see category and subcategory added$/) do
  page.should have_content("Project Category Added")
  i = 1
  until find(:xpath, "//table/tbody/tr[#{i}]/td[1]").text == @category_name

    i += 1
  end
  p i
  #find(:xpath, "/html/body/div[3]/table/tbody/tr/td").text == category_name
  find(:xpath, "//table/tbody/tr[#{i}]/td[2]").text == "#{@subcategory_name} (#{@points} pts)"
  find(:xpath, "//table/tbody/tr[#{i}]/td[3]").value == @points
end

When(/^I enter all the fields except category name field$/) do
  @user_details = UserDetails.new
  @category_name = " "
  @subcategory_name = @user_details.get_subcategory_name
  @subcategory_desc = @user_details.get_subcategory_desc
  @points = "10"
  add_category_with_all_details_required(@category_name,@subcategory_name, @subcategory_desc, @points)
end

Then(/^I should see error "(.*?)"$/) do |error_description|
  page.should have_content(error_description)
end

When(/^I enter all the fields except subcategory name field$/) do
  @user_details = UserDetails.new
  @category_name = @user_details.get_category_name
  @subcategory_name = ""
  @subcategory_desc = @user_details.get_subcategory_desc
  @points = "10"
  add_category_with_all_details_required(@category_name,@subcategory_name, @subcategory_desc, @points)
end

When(/^I enter all the fields except subcategory description field$/) do
  @user_details = UserDetails.new
  @category_name = @user_details.get_category_name
  @subcategory_name = @user_details.get_subcategory_name
  @subcategory_desc = ""
  @points = "10"
  add_category_with_all_details_required(@category_name,@subcategory_name, @subcategory_desc, @points)
end

When(/^I enter all the fields except subcategory points field$/) do
  @user_details = UserDetails.new
  @category_name = @user_details.get_category_name
  @subcategory_name = @user_details.get_subcategory_name
  @subcategory_desc = @user_details.get_subcategory_desc
  @points = ""
  add_category_with_all_details_required(@category_name,@subcategory_name, @subcategory_desc, @points)
end

When(/^I Add extra Criteria along with other details to add a category$/) do
  @user_details = UserDetails.new
  @category_name = @user_details.get_category_name
  @subcategory_name = @user_details.get_subcategory_name
  @subcategory_desc = @user_details.get_subcategory_desc
  @points = "10"
  @additional_subcategory_desc = @user_details.get_subcategory_desc
  @additional_subcategory_points = "20"
  add_category_with_all_details_required_with_additional_criteria(@category_name,@subcategory_name, @subcategory_desc, @points, @additional_subcategory_desc, @additional_subcategory_points )
end

Then(/^I should be able to see criteria point as the sum of 2 criteria$/) do
  i = 1
  until find(:xpath, "//table/tbody/tr[#{i}]/td[1]").text == @category_name

    i += 1
  end
  p i
  #find(:xpath, "/html/body/div[3]/table/tbody/tr/td").text == category_name
  @total_points = (@points + @additional_subcategory_points)
  find(:xpath, "//table/tbody/tr[#{i}]/td[2]").text == "#{@subcategory_name} (#{@total_points} pts)"
  find(:xpath, "//table/tbody/tr[#{i}]/td[3]").value == @total_points
end



Given(/^I have filled in criteria details$/) do
  step "I enter all the fields"
end

When(/^I add a subcategory$/) do
  first(:xpath, ".//*[@id='add_subcat']").click
  #first(:button, "Add Subcategory").click
  @subcategory_1_name = @user_details.get_subcategory_name
  @subcategory_1_desc = @user_details.get_subcategory_desc
  @points_1 = "10"
  add_subcategory(@subcategory_1_name, @subcategory_1_desc, @points_1)
end

Then(/^I should see category and two subcategory added$/) do
  i = 1
  p @category_name
  until find(:xpath, "//table/tbody/tr[#{i}]/td[1]").text == @category_name

    i += 1
  end
  p i
  sleep 10
  #find(:xpath, "/html/body/div[3]/table/tbody/tr/td").text == category_name
  #p find(:css, "#category_settings_table.table tbody tr td br").text
  @expected_table_values = find(:xpath, ".//*[@id='category_settings_table']/tbody/tr[#{i}]/td[2]").text
  @expected_table_values.should include("#{@subcategory_name} (#{@points} pts)")
  @expected_table_values.should include("#{@subcategory_1_name} (#{@points_1} pts)")
  #p find(:xpath, ".//*[@id='category_settings_table']/tbody/tr[#{i}]/td[2]/br[1]").text
  #find(:xpath, ".//*[@id='category_settings_table']tbody/tr[#{i}]/td[2]").text == "#{@subcategory_name} (#{@points} pts)"
  #find(:xpath, ".//*[@id='category_settings_table']tbody/tr[#{i}]/td[2]/br[2]").text == "#{@subcategory_1_name} (#{@points_1} pts)"
  #@total_points = (@points + @points_1)
  find(:xpath, "//table/tbody/tr[#{i}]/td[3]").value == @total_points
end

When(/^I delete the added category$/) do
  i = 1
  until find(:xpath, "//table/tbody/tr[#{i}]/td[1]").text == @category_name

    i += 1
  end
  p i
  find(:xpath, ".//*[@id='category_settings_table']/tbody/tr[#{i}]/td[4]/button").click
  find(:xpath, "//html/body/div[4]/div[2]/a[2]").click
end

Then(/^I should not see the added category$/) do
  #@project_category_table = find(:xpath, ".//*[@id='category_settings_table']").value
  page.has_content?(@category_name)
  #p @project_category_table
  #@project_category_table.should_not include(@category_name)
  #find(:xpath, "//table/tbody/tr[#{i}]/td[1]").text == @category_name
end

When(/^I delete all the entries$/) do
  @table_size = page.all('table#category_settings_table tbody tr').count
  p @table_size

  row_deleted = 1

  while row_deleted <= @table_size
    find(:xpath, ".//*[@id='category_settings_table']/tbody/tr[1]/td[4]/button").click
    sleep 1
    find(:xpath, "//html/body/div[4]/div[2]/a[2]").click
    row_deleted = row_deleted + 1
  end

end

Then(/^I should see no entries in the table$/) do
  page.all('table#category_settings_table tbody tr').count.should == 1
  page.has_content?("No results found.")
end